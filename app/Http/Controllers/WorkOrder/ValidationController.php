<?php

namespace App\Http\Controllers\WorkOrder;

use App\Http\Controllers\BaseController;
use App\Http\Docs\AuthDocs;
use App\Http\Docs\WorkOrderValidateDocs;
use App\Http\Requests\WorkOrder\IndexWorkOrderRequest;
use App\Http\Requests\WorkOrder\StoreValidationWorkOrderRequest;
use App\Http\Requests\WorkOrder\StoreWorkOrderRequest;
use App\Http\Requests\WorkOrder\UpdateStatusValidationWorkOrderRequest;
use App\Http\Resources\WorkOrder\ListWoValidationResource;
use App\Services\WorkOrder\ValidationWoService;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;   // ✅ ditambahkan
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Work Order - Validation', 'Endpoint untuk validasi Work Order')]
class ValidationController extends BaseController
{
    public function __construct(protected ValidationWoService $validationWoService) {}

    /**
     * List data validasi WO
     */
    #[Endpoint('List data validasi WO', 'Menampilkan daftar Work Order yang perlu divalidasi beserta filter dan paginasi.')]
    #[QueryParam('search', 'string', 'Kata kunci pencarian', required: false, example: 'WO-2025')]
    #[QueryParam('status', 'string', 'Filter status', required: false, example: 'on_progress')]
    #[QueryParam('filter_column[ai.work_order_date][range][0]', 'string', 'Tanggal mulai rentang filter Work Order Date (format: YYYY-MM-DD)', required: false, example: '2026-08-05')]
    #[QueryParam('filter_column[ai.work_order_date][range][1]', 'string', 'Tanggal akhir rentang filter Work Order Date (format: YYYY-MM-DD)', required: false, example: '2026-08-07')]
    #[QueryParam('per_page', 'integer', 'Jumlah data per halaman', required: false, example: 15)]
    #[QueryParam('page', 'integer', 'Halaman ke-', required: false, example: 1)]
    #[Response(WorkOrderValidateDocs::LIST_VALIDATION_WO, status: 200, description: 'Berhasil')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    public function index(IndexWorkOrderRequest $request)
    {
        try {
            $data = $this->validationWoService->index($request->all());
            return $this->successResponse(new ListWoValidationResource($data), 'Berhasil mengambil data validasi WO');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    /**
     * List semua status validasi WO
     */
    #[Endpoint('List semua status validasi WO', 'Menampilkan seluruh opsi status validasi Work Order.')]
    #[Response(WorkOrderValidateDocs::LIST_STATUS, status: 200, description: 'Berhasil')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    public function listStatus()
    {
        try {
            $data = $this->validationWoService->showStatuses();
            return $this->successResponse($data, 'Berhasil mengambil data validasi WO');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    /**
     * Detail validasi WO
     */
    #[Endpoint('Detail validasi WO', 'Menampilkan detail satu Work Order beserta data validasinya.')]
    #[UrlParam('id', 'string', 'ID Work Order', required: true, example: 'WO-001')]
    #[Response(WorkOrderValidateDocs::DETAIL_VALIDATION_WO, status: 200, description: 'Berhasil')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    public function show(string $id)
    {
        try {
            $data = $this->validationWoService->showWo($id);
            return $this->successResponse($data, 'Berhasil mengambil detail validasi WO');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    /**
     * Simpan data validasi WO
     *
     * Autentikasi menggunakan header X-API-Key, bukan Bearer Token.
     * Contoh penggunaan:
     *   X-API-Key: your-api-key
     */
    #[Endpoint('Simpan data validasi WO', 'Menyimpan data validasi Work Order baru. Autentikasi menggunakan header X-API-Key.')]
    #[Header('X-API-Key', example: 'your-api-key')]
    #[BodyParam('assessment_type', 'string', 'Tipe assessment (hanya PSB)', required: true, example: 'PSB')]

    #[BodyParam('customer', 'object', 'Data customer', required: true, example: '{"customer_name":"PT. ABC","address":"Jl. Merdeka 1","pppoe_secret":"secret123","branch_id":"B001","branch_name":"Kantor Pusat","regional_name":"Regional 1"}')]
    #[BodyParam('customer.customer_name', 'string', 'Nama customer', required: true, example: 'PT. ABC')]
    #[BodyParam('customer.address', 'string', 'Alamat customer', required: true, example: 'Jl. Merdeka 1')]
    #[BodyParam('customer.pppoe_secret', 'string', 'PPPoE Secret', required: true, example: 'secret123')]
    #[BodyParam('customer.branch_id', 'string', 'ID Cabang', required: true, example: 'B001')]
    #[BodyParam('customer.branch_name', 'string', 'Nama Cabang', required: true, example: 'Kantor Pusat')]
    #[BodyParam('customer.regional_name', 'string', 'Nama Regional', required: true, example: 'Regional 1')]

    #[BodyParam('technical_data', 'object', 'Data teknis', required: true, example: '{"area_id":"A001","area_name":"Wilayah 1","area_code":"W1","area_primary_id":null,"area_primary_name":null,"area_primary_code":null,"splitter_id":100,"spliter":"Spliter 1","customer_port":2}')]
    #[BodyParam('technical_data.area_id', 'string', 'ID Area', required: true, example: 'A001')]
    #[BodyParam('technical_data.area_name', 'string', 'Nama Area', required: true, example: 'Wilayah 1')]
    #[BodyParam('technical_data.area_code', 'string', 'Kode Area', required: true, example: 'W1')]
    #[BodyParam('technical_data.area_primary_id', 'string', 'ID Area Primer (nullable)', required: false, example: '')]
    #[BodyParam('technical_data.area_primary_name', 'string', 'Nama Area Primer (nullable)', required: false, example: '')]
    #[BodyParam('technical_data.area_primary_code', 'string', 'Kode Area Primer (nullable)', required: false, example: '')]
    #[BodyParam('technical_data.splitter_id', 'integer', 'ID Splitter', required: true, example: 100)]
    #[BodyParam('technical_data.spliter', 'string', 'Nama Splitter', required: true, example: 'Spliter 1')]
    #[BodyParam('technical_data.customer_port', 'integer', 'Port Customer', required: true, example: 2)]

    #[BodyParam('work_order', 'object', 'Data work order', required: true, example: '{"wo_id":"WO-001","work_order_date":"2023-01-01","start_work_order":"2023-01-01","end_work_order":"2023-01-02","duration_minute":120,"detail":{"team_id":"T01","team_name":"Tim 1","sales_distance":3,"technician_distance":2,"dropcore":100,"cable_status":"baru","cable_history_id":null,"cable_detail_history_id":null}}')]
    #[BodyParam('work_order.wo_id', 'string', 'ID Work Order', required: true, example: 'WO-001')]
    #[BodyParam('work_order.work_order_date', 'string', 'Tanggal Work Order (YYYY-MM-DD)', required: true, example: '2023-01-01')]
    #[BodyParam('work_order.start_work_order', 'string', 'Tanggal mulai Work Order (YYYY-MM-DD)', required: true, example: '2023-01-01')]
    #[BodyParam('work_order.end_work_order', 'string', 'Tanggal selesai Work Order (YYYY-MM-DD)', required: true, example: '2023-01-02')]
    #[BodyParam('work_order.duration_minute', 'integer', 'Durasi menit', required: true, example: 120)]
    #[BodyParam('work_order.detail', 'object', 'Detail work order', required: true, example: '{"team_id":"T01","team_name":"Tim 1","sales_distance":3,"technician_distance":2,"dropcore":100,"cable_status":"baru","cable_history_id":null,"cable_detail_history_id":null}')]
    #[BodyParam('work_order.detail.team_id', 'string', 'ID Tim', required: true, example: 'T01')]
    #[BodyParam('work_order.detail.team_name', 'string', 'Nama Tim', required: true, example: 'Tim 1')]
    #[BodyParam('work_order.detail.sales_distance', 'integer', 'Jarak sales', required: true, example: 3)]
    #[BodyParam('work_order.detail.technician_distance', 'integer', 'Jarak teknisi', required: true, example: 2)]
    #[BodyParam('work_order.detail.dropcore', 'integer', 'Dropcore', required: true, example: 100)]
    #[BodyParam('work_order.detail.cable_status', 'string', 'Status kabel', required: true, example: 'baru')]
    #[BodyParam('work_order.detail.cable_history_id', 'integer', 'ID Riwayat Kabel (nullable)', required: false, example: 0)]
    #[BodyParam('work_order.detail.cable_detail_history_id', 'integer', 'ID Detail Riwayat Kabel (nullable)', required: false, example: 0)]

    #[BodyParam('pic', 'object[]', 'PIC', required: true, example: '[{"role":"teknisi","nip":"123456","nick_name":"Tek1","full_name":"Teknisi Satu","order":1}]')]
    #[BodyParam('pic[].role', 'string', 'Role PIC', required: true, example: 'teknisi')]
    #[BodyParam('pic[].nip', 'string', 'NIP PIC', required: true, example: '123456')]
    #[BodyParam('pic[].nick_name', 'string', 'Nick Name PIC', required: true, example: 'Tek1')]
    #[BodyParam('pic[].full_name', 'string', 'Nama Lengkap PIC', required: true, example: 'Teknisi Satu')]
    #[BodyParam('pic[].order', 'integer', 'Urutan PIC (unique)', required: true, example: 1)]

    #[BodyParam('step', 'object[]', 'Langkah/langkah work order', required: true, example: '[{"step_id":"S1","step_code":"STEP-1","step_name":"Survey","order_number":1,"submitted_at":"2023-01-01","data_values":[{"data_code":"DC-1","data_name":"Foto Site","type_data":"image","value":"site.jpg"}]}]')]
    #[BodyParam('step[].step_id', 'string', 'ID Step', required: true, example: 'S1')]
    #[BodyParam('step[].step_code', 'string', 'Kode Step', required: true, example: 'STEP-1')]
    #[BodyParam('step[].step_name', 'string', 'Nama Step', required: true, example: 'Survey')]
    #[BodyParam('step[].order_number', 'integer', 'Nomor Urut Step', required: true, example: 1)]
    #[BodyParam('step[].submitted_at', 'string', 'Tanggal Submit Step (YYYY-MM-DD)', required: true, example: '2023-01-01')]
    #[BodyParam('step[].data_values', 'object[]', 'Data value per step', required: true, example: '[{"data_code":"DC-1","data_name":"Foto Site","type_data":"image","value":"site.jpg"}]')]
    #[BodyParam('step[].data_values[].data_code', 'string', 'Kode Data', required: true, example: 'DC-1')]
    #[BodyParam('step[].data_values[].data_name', 'string', 'Nama Data', required: true, example: 'Foto Site')]
    #[BodyParam('step[].data_values[].type_data', 'string', 'Tipe Data', required: true, example: 'image')]
    #[BodyParam('step[].data_values[].value', 'string', 'Nilai Data', required: true, example: 'site.jpg')]

    #[Response(WorkOrderValidateDocs::STORE_VALIDATION_WO, status: 200, description: 'Berhasil')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'X-API-Key tidak dikirim, tidak valid, atau sudah expired')]
    public function store(StoreWorkOrderRequest $request)
    {
        try {
            $data = $this->validationWoService->storeWo($request);
            return $this->successResponse($data, 'Berhasil menyimpan data validasi WO');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    /**
     * Update data validasi WO
     */
    #[Endpoint('Update data validasi WO', 'Memperbarui data validasi Work Order beserta hasil assessment-nya.')]
    #[UrlParam('id', 'string', 'ID Work Order', required: true, example: 'WO-001')]
    #[BodyParam('status', 'string', 'Status validasi', required: true, example: 'validated')]
    #[BodyParam('notes', 'string', 'Catatan validasi', required: false, example: 'OK')]

    #[BodyParam('assessments', 'object[]', 'Daftar assessment', required: true, example: '[{"assessment":{"id":"A-1","code":"KTP","name":"Verifikasi KTP","total_score":100},"categories":[{"category":{"criteria_id":"C-1","criteria_name":"Validasi","category_id":"CA-1","category_name":"Identitas","criteria_type_id":"CT-1","criteria_type_name":"Mandatory","operation_id":null,"score_type_id":"ST-1","max_score":50,"obtained_score":50,"calculate_value":null,"description":"OK"},"item":{"criterias":[{"step_code":"STEP-1","variable_key":"VAR-1","type_data":"string","value":"123"}],"rules":[{"id":"RULE-1","rule":"data harus diisi","is_checked":true}],"notes":"Lolos"}}]}]')]
    #[BodyParam('assessments[].assessment', 'object', 'Objek assessment', required: true, example: '{"id":"A-1","code":"KTP","name":"Verifikasi KTP","total_score":100}')]
    #[BodyParam('assessments[].assessment.id', 'string', 'ID assessment', required: true, example: 'A-1')]
    #[BodyParam('assessments[].assessment.code', 'string', 'Kode assessment', required: true, example: 'KTP')]
    #[BodyParam('assessments[].assessment.name', 'string', 'Nama assessment', required: true, example: 'Verifikasi KTP')]
    #[BodyParam('assessments[].assessment.total_score', 'number', 'Total skor', required: true, example: 100)]
    #[BodyParam('assessments[].categories', 'object[]', 'Daftar kategori', required: true, example: '[{"category":{"criteria_id":"C-1"},"item":{"notes":"Lolos"}}]')]
    #[BodyParam('assessments[].categories[].category', 'object', 'Objek kategori', required: true, example: '{"criteria_id":"C-1","criteria_name":"Validasi","category_id":"CA-1","category_name":"Identitas","criteria_type_id":"CT-1","criteria_type_name":"Mandatory","operation_id":null,"score_type_id":"ST-1","max_score":50,"obtained_score":50,"calculate_value":null,"description":"OK"}')]
    #[BodyParam('assessments[].categories[].category.criteria_id', 'string', 'ID Kriteria', required: true, example: 'C-1')]
    #[BodyParam('assessments[].categories[].category.criteria_name', 'string', 'Nama Kriteria', required: true, example: 'Validasi')]
    #[BodyParam('assessments[].categories[].category.category_id', 'string', 'ID Kategori', required: true, example: 'CA-1')]
    #[BodyParam('assessments[].categories[].category.category_name', 'string', 'Nama Kategori', required: true, example: 'Identitas')]
    #[BodyParam('assessments[].categories[].category.criteria_type_id', 'string', 'ID Tipe Kriteria', required: true, example: 'CT-1')]
    #[BodyParam('assessments[].categories[].category.criteria_type_name', 'string', 'Nama Tipe Kriteria', required: true, example: 'Mandatory')]
    #[BodyParam('assessments[].categories[].category.operation_id', 'string', 'ID Operasi (nullable)', required: false, example: '')]
    #[BodyParam('assessments[].categories[].category.score_type_id', 'string', 'ID Tipe Skor', required: true, example: 'ST-1')]
    #[BodyParam('assessments[].categories[].category.max_score', 'number', 'Maksimal skor', required: true, example: 50)]
    #[BodyParam('assessments[].categories[].category.obtained_score', 'number', 'Skor yang didapat', required: true, example: 50)]
    #[BodyParam('assessments[].categories[].category.calculate_value', 'number', 'Nilai hasil perhitungan (nullable)', required: false, example: 0)]
    #[BodyParam('assessments[].categories[].category.description', 'string', 'Deskripsi nilai', required: false, example: 'OK')]
    #[BodyParam('assessments[].categories[].item', 'object', 'Objek item', required: true, example: '{"criterias":[{"step_code":"STEP-1","variable_key":"VAR-1","type_data":"string","value":"123"}],"rules":[{"id":"RULE-1","rule":"data harus diisi","is_checked":true}],"notes":"Lolos"}')]
    #[BodyParam('assessments[].categories[].item.criterias', 'object[]', 'Daftar kriteria pada item', required: true, example: '[{"step_code":"STEP-1","variable_key":"VAR-1","type_data":"string","value":"123"}]')]
    #[BodyParam('assessments[].categories[].item.criterias[].step_code', 'string', 'Kode step', required: true, example: 'STEP-1')]
    #[BodyParam('assessments[].categories[].item.criterias[].variable_key', 'string', 'Key variable', required: true, example: 'VAR-1')]
    #[BodyParam('assessments[].categories[].item.criterias[].type_data', 'string', 'Tipe data variable', required: false, example: 'string')]
    #[BodyParam('assessments[].categories[].item.criterias[].value', 'string', 'Nilai variable', required: false, example: '123')]
    #[BodyParam('assessments[].categories[].item.rules', 'object[]', 'Daftar aturan', required: true, example: '[{"id":"RULE-1","rule":"data harus diisi","is_checked":true}]')]
    #[BodyParam('assessments[].categories[].item.rules[].id', 'string', 'ID aturan', required: true, example: 'RULE-1')]
    #[BodyParam('assessments[].categories[].item.rules[].rule', 'string', 'Rule/aturan', required: true, example: 'data harus diisi')]
    #[BodyParam('assessments[].categories[].item.rules[].is_checked', 'boolean', 'Status checked rule', required: false, example: true)]
    #[BodyParam('assessments[].categories[].item.notes', 'string', 'Catatan pada item', required: false, example: 'Lolos')]

    #[Response(WorkOrderValidateDocs::UPDATE_VALIDATION_WO, status: 200, description: 'Berhasil')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    public function update(StoreValidationWorkOrderRequest $request, string $id)
    {
        try {
            $data = $this->validationWoService->updateWo($request->all(), $id);
            return $this->successResponse($data, 'Berhasil menyimpan data validasi WO');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    /**
     * Update status progress validasi WO
     */
    #[Endpoint('Update status progress validasi WO', 'Mengubah status progress dari sebuah Work Order.')]
    #[UrlParam('id', 'string', 'ID Work Order', required: true, example: 'WO-001')]
    #[BodyParam('status', 'string', 'Status baru. Nilai yang valid: DRAFT, IN_PROGRESS, VALIDATED, REJECTED.', required: true, example: 'IN_PROGRESS')]
    #[Response(WorkOrderValidateDocs::UPDATE_STATUS_PROGRESS, status: 200, description: 'Berhasil')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    public function updateOnProgress(UpdateStatusValidationWorkOrderRequest $request, string $id)
    {
        try {
            $param = $request->all();
            $data = $this->validationWoService->updateStatusProgress($id, $param['status']);
            return $this->successResponse($data, 'Berhasil update status progress validasi WO');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }

    /**
     * Ambil WO yang belum selesai
     */
    #[Endpoint('Ambil WO yang belum selesai', 'Menampilkan daftar Work Order yang belum selesai divalidasi.')]
    #[Response(WorkOrderValidateDocs::WO_NOT_COMPLETE, status: 200, description: 'Berhasil')]
    #[Response(AuthDocs::UNAUTHORIZED, status: 401, description: 'Token tidak dikirim, tidak valid, atau sudah expired')]
    public function getWoNotComplete()
    {
        try {
            $data = $this->validationWoService->getWoNotComplete();
            return $this->successResponse($data, 'Berhasil mengambil WO yang belum selesai');
        } catch (\Throwable $th) {
            return api()->handle($th);
        }
    }
}