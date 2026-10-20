import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import { execSync } from 'child_process';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const args = process.argv.slice(2);
const command = args[0];
const documentName = args.slice(1).join(' ');

if (!command || (command !== 'pr' && !documentName)) {
  console.error("Gunakan format:");
  console.error("  npm run create brief [nama brief]");
  console.error("  npm run create task [nama task]");
  console.error("  npm run create both [nama dokumen]");
  console.error("  npm run create api [nama api contract]");
  console.error("  npm run create fixing [nama fixing]");
  console.error("  npm run create pr [nama file] [target branch (dev/staging/main)]");
  process.exit(1);
}

// Format Tanggal dan Waktu
const now = new Date();
const date = String(now.getDate()).padStart(2, '0');
const monthNames = ["januari", "februari", "maret", "april", "mei", "juni", 
                    "juli", "agustus", "september", "oktober", "november", "desember"];
const monthName = monthNames[now.getMonth()];
const monthNum = String(now.getMonth() + 1).padStart(2, '0');
const year = now.getFullYear();
const hours = String(now.getHours()).padStart(2, '0');
const minutes = String(now.getMinutes()).padStart(2, '0');

// Fungsi untuk membuat file
function createFile(type, name) {
  const folderName = `${date} ${monthName} ${year}`;
  const fileName = `${type}_${year}${monthNum}${date}_${hours}${minutes}_${name.toLowerCase().replace(/\s+/g, '_')}.md`;
  const dirPath = path.join(__dirname, 'documentation', type, folderName);
  const filePath = path.join(dirPath, fileName);

  try {
    // 1. Buat folder secara rekursif
    fs.mkdirSync(dirPath, { recursive: true });

    // 2. Isi konten default file
    let title, content;
    if (type === 'api') {
      title = 'API Contract';
      content = `# API Contract: ${name}\nTanggal: ${date} ${monthName} ${year} ${hours}:${minutes}\n\n## Endpoint\n\n## Request\n\n## Response\n\n## Example\n\n---`;
    } else if (type === 'fixing') {
      title = 'Fix';
      content = `# Fix Documentation: ${name}\nTanggal: ${date} ${monthName} ${year} ${hours}:${minutes}\n\n## Problem Description:\n\n## Root Cause Analysis:\n\n## Solution Applied:\n\n### Files Modified:\n\n### Changes Made:\n\n## Key Fixes:\n\n## Impact Assessment:\n\n## Testing Checklist:\n\n## Affected Components:\n\n## Lessons Learned:\n\n---`;
    } else {
      title = type === 'brief' ? 'Brief' : 'Task';
      content = `# ${title}: ${name}\nTanggal: ${date} ${monthName} ${year} ${hours}:${minutes}\n\n---`;
    }

    // 3. Tulis file
    fs.writeFileSync(filePath, content);
    
    const displayType = type === 'api' ? 'API Contract' : (type === 'brief' ? 'Brief' : (type === 'fixing' ? 'Fix' : 'Task'));
    console.log(`\n${displayType} berhasil dibuat!`);
    console.log(`Path: documentation/${type}/${folderName}/${fileName}\n`);
    
    return true;
  } catch (err) {
    console.error(`Gagal membuat ${type}:`, err);
    return false;
  }
}

function getGitCommits(count = 3) {
  try {
    const output = execSync('git log -n ' + count + ' --pretty=format:"%s%n%b" --no-merges', { encoding: 'utf-8' });
    const commits = output.trim().split('\n\n').filter(c => c.trim());
    return commits.map(c => {
      const lines = c.trim().split('\n');
      return {
        subject: lines[0] || '',
        body: lines.slice(1).join('\n').trim() || ''
      };
    });
  } catch (e) {
    return [];
  }
}

function createPRDescription(targetBranch, commits) {
  const commitSummary = commits.map(c => c.subject).join('\n- ');
  
  let hasNewFeature = false;
  let isBugFix = false;
  let isRefactor = false;
  
  const lowerSummary = commitSummary.toLowerCase();
  if (lowerSummary.includes('fitur') || lowerSummary.includes('penambahan') || lowerSummary.includes('menambahkan') || lowerSummary.includes('feat') || lowerSummary.includes('feature')) hasNewFeature = true;
  if (lowerSummary.includes('bug') || lowerSummary.includes('fix') || lowerSummary.includes('perbaikan') || lowerSummary.includes('bugfix')) isBugFix = true;
  if (lowerSummary.includes('refactor') || lowerSummary.includes('refaktoring') || lowerSummary.includes('perbaikan struktur')) isRefactor = true;
  
  let typeCheckboxes = '';
  if (hasNewFeature) typeCheckboxes += '- [x] Fitur baru\n';
  if (isBugFix) typeCheckboxes += '- [x] Bug fix\n';
  if (isRefactor) typeCheckboxes += '- [x] Refactor (tidak mengubah behavior)\n';
  if (!hasNewFeature && !isBugFix && !isRefactor) {
    typeCheckboxes = '- [ ] Fitur baru\n- [ ] Bug fix\n- [ ] Refactor (tidak mengubah behavior)\n- [ ] Chore / tooling / config\n';
  }
  
  const devChecked = targetBranch === 'dev' ? '[x]' : '[ ]';
  const stagingChecked = targetBranch === 'staging' ? '[x]' : '[ ]';
  const mainChecked = targetBranch === 'main' ? '[x]' : '[ ]';
  
  const prContent = `## 📌 Ringkasan

<!-- Jelaskan singkat apa yang diubah dan kenapa -->
${commitSummary}

**Target branch:**

- ${devChecked} \`dev\` — integrasi fitur/fix harian
- ${stagingChecked} \`staging\` — promosi batch perubahan ke QA
- ${mainChecked} \`main\` — rilis ke production

**Terkait ticket/issue:** #

## 🧩 Jenis Perubahan
${typeCheckboxes}
## ✅ Checklist Dasar (wajib untuk semua target branch)

- [ ] Kode bisnis baru ada di \`src/features/[nama-fitur]/\` mengikuti pola domain/data/presentation
- [ ] \`npm run lint\` lulus lokal
- [ ] \`npm test\` lulus lokal
- [ ] \`npm run build\` lulus lokal
- [ ] Sudah self-review diff sendiri
- [ ] Tidak ada \`console.log\` atau kode debug tertinggal
- [ ] Tidak ada \`any\` tanpa alasan yang jelas
- [ ] Tidak ada file/config personal ikut ter-commit (env lokal, host ngrok, dll)

<!-- Bagian di bawah ini opsional — isi kalau relevan dengan target branch, hapus kalau tidak -->

## 🔍 Tambahan kalau target \`staging\`

- [ ] Semua fitur di PR ini sudah lengkap & sudah di-test manual sendiri
- [ ] Sudah di-review minimal 1 reviewer di level \`dev\`

**Fokus testing untuk QA:**
<!-- Area/skenario/edge case spesifik yang perlu dicek QA -->

## 🚀 Tambahan kalau target \`main\`

- [ ] Sudah lolos QA di staging (sign-off QA/PM)
- [ ] Tidak ada breaking change tanpa rencana migrasi yang jelas
- [ ] Changelog / release notes sudah diupdate (jika tim pakai)
- [ ] Env var / konfigurasi production baru (jika ada) sudah disiapkan di server/hosting
- [ ] Disetujui oleh reviewer **dan** tech lead/PM

**Catatan deployment:**
<!-- Migrasi data, env var baru, urutan deploy khusus, dsb -->

**Rencana rollback:**
<!-- Langkah kalau rilis ini perlu di-revert -->

## 🖼️ Screenshot / Demo (jika ada perubahan UI)

## 📝 Catatan untuk Reviewer`;
  
  return prContent;
}

// Eksekusi berdasarkan command
switch (command) {
  case 'brief':
    createFile('brief', documentName);
    break;
  case 'task':
    createFile('task', documentName);
    break;
  case 'api':
    createFile('api', documentName);
    break;
  case 'fixing':
    createFile('fixing', documentName);
    break;
  case 'both':
    console.log('Membuat Brief dan Task...\n');
    const briefSuccess = createFile('brief', documentName);
    const taskSuccess = createFile('task', documentName);
    
    if (briefSuccess && taskSuccess) {
      console.log('Semua dokumen berhasil dibuat!');
    }
    break;
  case 'pr':
    const prArgs = args.slice(1);
    const prName = prArgs.slice(0, -1).join(' ');
    const prTargetBranch = prArgs[prArgs.length - 1] || 'dev';
    
    if (!prName) {
      console.error("Nama file PR wajib diberikan.");
      process.exit(1);
    }
    
    const commits = getGitCommits(3);
    if (commits.length === 0) {
      console.error("Tidak ada commit terbaru yang ditemukan.");
      process.exit(1);
    }
    const prContent = createPRDescription(prTargetBranch, commits);
    
    const prFileName = `pr_${year}${monthNum}${date}_${hours}${minutes}_${prName.toLowerCase().replace(/\s+/g, '_')}.md`;
    const prDirPath = path.join(__dirname, 'documentation', 'pr');
    const prFilePath = path.join(prDirPath, prFileName);
    
    try {
      fs.mkdirSync(prDirPath, { recursive: true });
      fs.writeFileSync(prFilePath, prContent);
      console.log(`\nPR Description berhasil dibuat!`);
      console.log(`Path: documentation/pr/${prFileName}\n`);
    } catch (err) {
      console.error("Gagal membuat PR description:", err);
    }
    break;
  default:
    console.error("Command tidak valid. Gunakan 'brief', 'task', 'api', 'fixing', 'both', atau 'pr'");
    process.exit(1);
}
