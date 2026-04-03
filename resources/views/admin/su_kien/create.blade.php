@extends('admin.layout')

@section('title', 'Tao su kien')
@section('page-title', 'Tao su kien')

@php
    $defaultLayout = old('bo_cuc', ['banner', 'header', 'info', 'description', 'gallery']);
    $moduleMap = [
        'banner' => ['Anh bia', 'bi-card-image', 'Hero va hinh anh mo dau'],
        'header' => ['Tieu de', 'bi-type-h1', 'Tieu de va doan dan nhap'],
        'info' => ['Thong tin', 'bi-info-circle', 'Thoi gian, dia diem, chi tieu'],
        'description' => ['Noi dung', 'bi-text-paragraph', 'Phan mo ta chi tiet'],
        'gallery' => ['Gallery', 'bi-images', 'Thu vien anh ho tro'],
    ];
    $templatePayload = $templates->map(function ($tpl) use ($defaultLayout) {
        return [
            'id' => $tpl->ma_su_kien,
            'name' => $tpl->ten_su_kien,
            'type' => $tpl->ma_loai_su_kien,
            'location' => $tpl->dia_diem,
            'capacity' => $tpl->so_luong_toi_da,
            'points' => $tpl->diem_cong,
            'content' => $tpl->mo_ta_chi_tiet,
            'layout' => $tpl->bo_cuc ?: $defaultLayout,
        ];
    })->keyBy('id');
    $selectedMedia = collect(old('selected_media_ids', []))->map(fn ($id) => (int) $id)->all();
@endphp

@section('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<style>
body{background:radial-gradient(circle at top right,rgba(37,99,235,.12),transparent 28%),linear-gradient(180deg,#eef4ff 0%,#f8fafc 36%,#f8fafc 100%)}.create-event{max-width:1440px;margin:0 auto;padding-bottom:132px}.hero-grid,.page-grid{display:grid;gap:24px}.hero-grid{grid-template-columns:minmax(0,1.4fr) minmax(280px,.8fr);margin-bottom:24px}.page-grid{grid-template-columns:minmax(0,1.45fr) minmax(320px,.85fr);align-items:start}.hero-card,.panel,.submit-bar{background:rgba(255,255,255,.96);border:1px solid rgba(148,163,184,.16);border-radius:24px;box-shadow:0 20px 45px rgba(15,23,42,.08);backdrop-filter:blur(14px)}.hero-card{padding:28px 30px;position:relative;overflow:hidden}.hero-card:after{content:"";position:absolute;right:-60px;bottom:-80px;width:220px;height:220px;border-radius:999px;background:linear-gradient(135deg,rgba(59,130,246,.22),rgba(14,116,144,.06))}.hero-eyebrow{display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:999px;background:rgba(37,99,235,.1);color:#1d4ed8;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;margin-bottom:14px}.hero-card h2{font-size:clamp(28px,3vw,40px);font-weight:800;color:#0f172a;line-height:1.08;margin-bottom:12px;max-width:14ch}.hero-card p,.hint{color:#475569;line-height:1.7}.hero-meta,.main-stack,.side-stack,.layout-list{display:grid;gap:14px}.hero-meta{padding:24px}.stat{padding:16px 18px;border-radius:18px;background:linear-gradient(180deg,#f8fafc 0%,#eff6ff 100%);border:1px solid rgba(148,163,184,.14)}.stat span{display:block;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b;margin-bottom:6px}.stat strong{display:block;font-size:21px;color:#0f172a}.panel{padding:26px}.panel-head{display:flex;justify-content:space-between;gap:16px;align-items:start;margin-bottom:20px}.panel-title{font-size:20px;font-weight:800;color:#0f172a;margin-bottom:6px}.panel-subtitle{margin:0;color:#64748b;font-size:14px;line-height:1.6}.pill{display:inline-flex;align-items:center;gap:6px;padding:8px 12px;border-radius:999px;background:#eff6ff;color:#1d4ed8;font-size:12px;font-weight:700}.form-grid{display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:18px}.span-12{grid-column:span 12}.span-6{grid-column:span 6}.span-4{grid-column:span 4}.form-label{font-size:13px;font-weight:700;color:#1e293b;margin-bottom:8px}.form-control,.form-select{border-radius:16px;border:1px solid #dbe5f2;min-height:52px;padding:12px 16px;font-size:15px;box-shadow:none!important}.form-control:focus,.form-select:focus{border-color:#3b82f6;box-shadow:0 0 0 4px rgba(59,130,246,.12)!important}.field-note{display:block;color:#64748b;font-size:12px;margin-top:8px;line-height:1.5}.dropzone{border:1.5px dashed #bfd0e8;border-radius:22px;background:linear-gradient(180deg,#fbfdff 0%,#f1f5f9 100%);padding:22px;transition:.2s ease}.dropzone.drag{border-color:#2563eb;background:linear-gradient(180deg,#eff6ff 0%,#dbeafe 100%);transform:translateY(-2px)}.upload-surface{min-height:220px;display:grid;place-items:center;text-align:center;gap:12px;cursor:pointer;color:#475569}.upload-surface i{font-size:34px;color:#2563eb}.upload-title{font-size:18px;font-weight:700;color:#0f172a}.cover-preview{display:none;width:100%;min-height:260px;border-radius:18px;overflow:hidden;background:#e2e8f0}.cover-preview img,.gallery-preview img,.library-item img{width:100%;height:100%;object-fit:cover;display:block}.gallery-preview,.library-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(132px,1fr));gap:14px}.gallery-item,.library-item{position:relative;border-radius:18px;overflow:hidden;border:1px solid #dbe5f2;background:#fff}.gallery-item img,.library-item img{height:118px}.gallery-item button{position:absolute;top:10px;right:10px;width:28px;height:28px;border:none;border-radius:999px;background:rgba(15,23,42,.82);color:#fff}.gallery-caption,.library-caption{padding:10px 12px 12px;font-size:12px;color:#475569;line-height:1.5}.library-item{cursor:pointer;transition:.2s ease}.library-item.selected{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.12);transform:translateY(-2px)}.library-check{position:absolute;top:10px;right:10px;width:30px;height:30px;border-radius:999px;display:grid;place-items:center;background:rgba(15,23,42,.76);color:#fff;font-size:14px}.chip-row{display:flex;gap:10px;flex-wrap:wrap}.module-chip{border:1px solid #cbd5e1;background:#fff;color:#0f172a;border-radius:999px;padding:10px 14px;font-size:13px;font-weight:700}.module-chip:hover{border-color:#3b82f6;color:#1d4ed8}.layout-item{display:flex;align-items:center;gap:12px;padding:14px 16px;border-radius:18px;border:1px solid #dbe5f2;background:linear-gradient(180deg,#fff 0%,#f8fafc 100%)}.layout-handle{color:#94a3b8;cursor:grab;font-size:18px}.layout-badge{width:42px;height:42px;border-radius:14px;display:grid;place-items:center;background:#eff6ff;color:#1d4ed8;flex-shrink:0}.layout-copy strong{display:block;font-size:14px;color:#0f172a}.layout-copy span{display:block;font-size:12px;color:#64748b;margin-top:2px}.layout-remove{width:34px;height:34px;border:none;border-radius:999px;background:#fee2e2;color:#b91c1c;margin-left:auto;flex-shrink:0}.empty-layout,.error-box{border-radius:18px;padding:18px}.empty-layout{border:1px dashed #cbd5e1;text-align:center;color:#64748b;font-size:14px}.error-box{margin-bottom:18px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b}.error-box ul{margin:10px 0 0;padding-left:18px}#editor-container{min-height:360px;font-size:15px}.ql-toolbar.ql-snow{border-color:#dbe5f2;border-radius:18px 18px 0 0;padding:12px}.ql-container.ql-snow{border-color:#dbe5f2;border-radius:0 0 18px 18px;font-size:15px;min-height:300px}.submit-bar{position:fixed;left:calc(280px + 32px);right:32px;bottom:26px;padding:18px 24px;display:flex;justify-content:space-between;gap:16px;align-items:center;z-index:40}.submit-actions{display:flex;gap:12px;flex-wrap:wrap;align-items:center}.btn-premium{min-width:190px;min-height:52px;border-radius:16px;font-weight:700;display:inline-flex;align-items:center;justify-content:center;gap:8px}@media(max-width:1200px){.submit-bar{left:32px}}@media(max-width:992px){.hero-grid,.page-grid{grid-template-columns:1fr}.span-6,.span-4{grid-column:span 12}.submit-bar{left:16px;right:16px;bottom:16px;flex-direction:column;align-items:stretch}.submit-actions{width:100%}.submit-actions .btn{width:100%}}
</style>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.su-kien.store') }}" enctype="multipart/form-data" id="suKienForm">
    @csrf
    <input type="hidden" name="mo_ta_chi_tiet" id="mo_ta_chi_tiet" value="{{ old('mo_ta_chi_tiet') }}">
    <div id="selected-media-inputs"></div>

    <div class="create-event">
        @if ($errors->any())
            <div class="error-box">
                <strong>Vui long kiem tra lai thong tin truoc khi tao su kien.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="hero-grid">
            <div class="hero-card">
                <div class="hero-eyebrow"><i class="bi bi-stars"></i> Tao bai dang logic hon</div>
                <h2>Xay dung trang tao su kien de dien thong tin nhanh va ro rang.</h2>
                <p>Thong tin co ban duoc dat len truoc, noi dung va media di tiep theo, phan module nam rieng de keo tha va lap lai module trong cung mot bai viet.</p>
            </div>
            <div class="hero-card hero-meta">
                <div class="stat"><span>Templates</span><strong>{{ $templates->count() }}</strong></div>
                <div class="stat"><span>Media san co</span><strong>{{ $mediaKho->count() }}</strong></div>
                <div class="stat"><span>Bo cuc mac dinh</span><strong>{{ count($defaultLayout) }} modules</strong></div>
            </div>
        </div>

        <div class="page-grid">
            <div class="main-stack">
                <section class="panel">
                    <div class="panel-head">
                        <div>
                            <div class="panel-title">Thong tin co ban</div>
                            <p class="panel-subtitle">Dien ten, loai, lich trinh va chi so cua su kien truoc khi sang phan noi dung.</p>
                        </div>
                        <div class="pill"><i class="bi bi-calendar-plus"></i> Bat dau tu tren xuong duoi</div>
                    </div>
                    <div class="form-grid">
                        <div class="span-12">
                            <label class="form-label" for="ten_su_kien">Ten su kien</label>
                            <input type="text" class="form-control" id="ten_su_kien" name="ten_su_kien" value="{{ old('ten_su_kien') }}" placeholder="Nhap ten su kien" required>
                        </div>
                        <div class="span-6">
                            <label class="form-label" for="ma_loai_su_kien">Loai su kien</label>
                            <select class="form-select" id="ma_loai_su_kien" name="ma_loai_su_kien" required>
                                <option value="">Chon loai su kien</option>
                                @foreach ($loai as $item)
                                    <option value="{{ $item->ma_loai_su_kien }}" @selected(old('ma_loai_su_kien') == $item->ma_loai_su_kien)>{{ $item->ten_loai }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="span-6">
                            <label class="form-label" for="dia_diem">Dia diem</label>
                            <input type="text" class="form-control" id="dia_diem" name="dia_diem" value="{{ old('dia_diem') }}" placeholder="VD: Hoi truong A, Nha E">
                            <span class="field-note">Neu co dia diem, he thong se canh bao khi trung lich.</span>
                        </div>
                        <div class="span-6">
                            <label class="form-label" for="thoi_gian_bat_dau">Thoi gian bat dau</label>
                            <input type="datetime-local" class="form-control" id="thoi_gian_bat_dau" name="thoi_gian_bat_dau" value="{{ old('thoi_gian_bat_dau') }}" required>
                        </div>
                        <div class="span-6">
                            <label class="form-label" for="thoi_gian_ket_thuc">Thoi gian ket thuc</label>
                            <input type="datetime-local" class="form-control" id="thoi_gian_ket_thuc" name="thoi_gian_ket_thuc" value="{{ old('thoi_gian_ket_thuc') }}" required>
                        </div>
                        <div class="span-6">
                            <label class="form-label" for="so_luong_toi_da">So luong toi da</label>
                            <input type="number" min="1" class="form-control" id="so_luong_toi_da" name="so_luong_toi_da" value="{{ old('so_luong_toi_da') }}" placeholder="VD: 300">
                        </div>
                        <div class="span-6">
                            <label class="form-label" for="diem_cong">Diem cong</label>
                            <input type="number" min="0" class="form-control" id="diem_cong" name="diem_cong" value="{{ old('diem_cong') }}" placeholder="VD: 5">
                        </div>
                    </div>
                </section>

                <section class="panel">
                    <div class="panel-head">
                        <div>
                            <div class="panel-title">Noi dung chi tiet</div>
                            <p class="panel-subtitle">Soan noi dung trong mot khung lien mach, de khi xuat ra bai viet se gon va doc de theo bo cuc da chon.</p>
                        </div>
                    </div>
                    <div id="editor-container"></div>
                </section>

                <section class="panel">
                    <div class="panel-head">
                        <div>
                            <div class="panel-title">Media va thu vien anh</div>
                            <p class="panel-subtitle">Anh bia ho tro keo tha. Gallery cho phep chon nhieu anh tu may va tu thu vien co san.</p>
                        </div>
                        <div class="pill"><i class="bi bi-images"></i> Chon nhieu anh</div>
                    </div>
                    <div class="form-grid">
                        <div class="span-6">
                            <label class="form-label">Anh bia</label>
                            <div class="dropzone" id="coverDropzone">
                                <div class="cover-preview" id="coverPreview"><img id="coverPreviewImage" alt="cover preview"></div>
                                <div class="upload-surface" id="coverSurface">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                    <div class="upload-title">Keo anh vao day hoac bam de chon</div>
                                    <p class="hint mb-0">Anh bia nen ro net, ngang, ty le rong de phan hero dep hon.</p>
                                </div>
                                <input type="file" class="d-none" id="coverInput" name="anh_su_kien" accept="image/*">
                            </div>
                        </div>
                        <div class="span-6">
                            <label class="form-label">Gallery upload</label>
                            <div class="dropzone" id="galleryDropzone">
                                <div class="upload-surface" id="gallerySurface">
                                    <i class="bi bi-collection"></i>
                                    <div class="upload-title">Keo tha nhieu anh cung luc</div>
                                    <p class="hint mb-0">Co the mo file picker nhieu lan, he thong se gom lai thanh mot gallery.</p>
                                </div>
                                <input type="file" class="d-none" id="galleryInput" name="gallery[]" accept="image/*" multiple>
                            </div>
                        </div>
                        <div class="span-12">
                            <div class="gallery-preview" id="galleryPreview"></div>
                        </div>
                        <div class="span-12">
                            <label class="form-label">Chon anh tu thu vien</label>
                            <p class="panel-subtitle mb-3">Bam de chon nhieu anh co san. Moi anh duoc copy vao gallery su kien, khong lam mat file goc.</p>
                            <div class="library-grid" id="libraryGrid">
                                @forelse ($mediaKho as $media)
                                    @php $picked = in_array($media->ma_phuong_tien, $selectedMedia, true); @endphp
                                    <button type="button" class="library-item {{ $picked ? 'selected' : '' }}" data-media-id="{{ $media->ma_phuong_tien }}">
                                        <img src="{{ asset('storage/' . $media->duong_dan_tep) }}" alt="{{ $media->ten_tep }}">
                                        <span class="library-check"><i class="bi {{ $picked ? 'bi-check2' : 'bi-plus-lg' }}"></i></span>
                                        <div class="library-caption">{{ \Illuminate\Support\Str::limit($media->ten_tep ?: basename($media->duong_dan_tep), 26) }}</div>
                                    </button>
                                @empty
                                    <div class="empty-layout">Chua co anh nao trong thu vien media.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <aside class="side-stack">
                <section class="panel">
                    <div class="panel-head">
                        <div>
                            <div class="panel-title">Ap dung template</div>
                            <p class="panel-subtitle">Lay nhanh bo cuc, noi dung va mot so thong so co san. Ten su kien hien tai se duoc giu nguyen.</p>
                        </div>
                    </div>
                    <div class="chip-row mb-3">
                        <select class="form-select" id="templateSelect">
                            <option value="">Mac dinh khong template</option>
                            @foreach ($templates as $tpl)
                                <option value="{{ $tpl->ma_su_kien }}">{{ $tpl->ten_su_kien }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-outline-primary" id="applyTemplateBtn"><i class="bi bi-lightning-charge"></i> Ap dung</button>
                    </div>
                    <p class="hint mb-0">Template se cap nhat loai su kien, dia diem, chi tieu, diem cong, noi dung va danh sach module.</p>
                </section>

                <section class="panel">
                    <div class="panel-head">
                        <div>
                            <div class="panel-title">Bo cuc bai dang</div>
                            <p class="panel-subtitle">Cac module mac dinh da duoc nap san. Ban co the them tiep, lap module va keo tha de doi thu tu.</p>
                        </div>
                    </div>
                    <div class="chip-row mb-3">
                        @foreach ($moduleMap as $key => $module)
                            <button type="button" class="module-chip" data-add-module="{{ $key }}"><i class="bi {{ $module[1] }}"></i> {{ $module[0] }}</button>
                        @endforeach
                    </div>
                    <div class="layout-list" id="layoutList"></div>
                </section>
            </aside>
        </div>

        <div class="submit-bar">
            <p class="hint mb-0">Niem tin luong form nay la: dien thong tin, kiem tra bo cuc, them media, roi tao su kien o thanh cuoi cung.</p>
            <div class="submit-actions">
                <a href="{{ route('admin.su-kien.index') }}" class="btn btn-light btn-premium border">Huy</a>
                <button type="submit" class="btn btn-primary btn-premium" id="submitBtn"><i class="bi bi-check2-circle"></i> Tao su kien</button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
const moduleCatalog=@json($moduleMap),templatePayload=@json($templatePayload),initialLayout=@json(array_values($defaultLayout)),initialDescription=@json(old('mo_ta_chi_tiet')),initialSelectedMedia=@json($selectedMedia);
const form=document.getElementById('suKienForm'),coverInput=document.getElementById('coverInput'),coverDropzone=document.getElementById('coverDropzone'),coverSurface=document.getElementById('coverSurface'),coverPreview=document.getElementById('coverPreview'),coverPreviewImage=document.getElementById('coverPreviewImage'),galleryInput=document.getElementById('galleryInput'),galleryDropzone=document.getElementById('galleryDropzone'),gallerySurface=document.getElementById('gallerySurface'),galleryPreview=document.getElementById('galleryPreview'),layoutList=document.getElementById('layoutList'),selectedMediaInputs=document.getElementById('selected-media-inputs'),submitBtn=document.getElementById('submitBtn');
const quill=new Quill('#editor-container',{theme:'snow',placeholder:'Viet noi dung su kien tai day...',modules:{toolbar:[[{header:[1,2,3,false]}],['bold','italic','underline'],[{list:'ordered'},{list:'bullet'}],['blockquote','link'],['clean']]}}); if(initialDescription){quill.clipboard.dangerouslyPasteHTML(initialDescription)} document.getElementById('mo_ta_chi_tiet').value=initialDescription||''; quill.on('text-change',()=>document.getElementById('mo_ta_chi_tiet').value=quill.root.innerHTML);
let layoutState=[...initialLayout],selectedMedia=new Set(initialSelectedMedia),galleryFiles=[];
function renderLayout(){if(!layoutState.length){layoutList.innerHTML='<div class="empty-layout">Bo cuc dang trong. Hay them it nhat mot module.</div>';return}layoutList.innerHTML=layoutState.map((key,index)=>{const item=moduleCatalog[key]||['Module','bi-grid',''];return `<div class="layout-item" data-index="${index}"><i class="bi bi-grip-vertical layout-handle"></i><div class="layout-badge"><i class="bi ${item[1]}"></i></div><div class="layout-copy"><strong>${item[0]}</strong><span>${item[2]}</span></div><input type="hidden" name="bo_cuc[]" value="${key}"><button type="button" class="layout-remove" data-remove-index="${index}"><i class="bi bi-x-lg"></i></button></div>`}).join('');}
function syncSelectedMedia(){selectedMediaInputs.innerHTML=[...selectedMedia].map(id=>`<input type="hidden" name="selected_media_ids[]" value="${id}">`).join('');document.querySelectorAll('[data-media-id]').forEach(item=>{const active=selectedMedia.has(Number(item.dataset.mediaId));item.classList.toggle('selected',active);const icon=item.querySelector('.library-check i');if(icon){icon.className=`bi ${active?'bi-check2':'bi-plus-lg'}`;}});}
function renderGallery(){galleryPreview.innerHTML=galleryFiles.map((file,index)=>`<div class="gallery-item"><img src="${URL.createObjectURL(file)}" alt="${file.name}"><button type="button" data-remove-gallery="${index}"><i class="bi bi-x"></i></button><div class="gallery-caption">${file.name}</div></div>`).join('');const dt=new DataTransfer();galleryFiles.forEach(file=>dt.items.add(file));galleryInput.files=dt.files;}
function showCover(file){if(!file){coverPreview.style.display='none';coverSurface.style.display='grid';return}const reader=new FileReader();reader.onload=e=>{coverPreviewImage.src=e.target.result;coverPreview.style.display='block';coverSurface.style.display='none'};reader.readAsDataURL(file);}
function bindDropzone(zone,input,onFiles){['dragenter','dragover'].forEach(evt=>zone.addEventListener(evt,e=>{e.preventDefault();zone.classList.add('drag')}));['dragleave','drop'].forEach(evt=>zone.addEventListener(evt,e=>{e.preventDefault();zone.classList.remove('drag')}));zone.addEventListener('click',e=>{if(e.target.closest('button'))return;input.click()});input.addEventListener('change',e=>{if(e.target.files?.length)onFiles(e.target.files)});zone.addEventListener('drop',e=>{if(e.dataTransfer?.files?.length)onFiles(e.dataTransfer.files)});}
bindDropzone(coverDropzone,coverInput,files=>{const file=[...files].find(f=>f.type.startsWith('image/'));if(!file)return;const dt=new DataTransfer();dt.items.add(file);coverInput.files=dt.files;showCover(file)}); bindDropzone(galleryDropzone,galleryInput,files=>{galleryFiles=[...galleryFiles,...[...files].filter(f=>f.type.startsWith('image/'))];renderGallery()});
document.addEventListener('click',e=>{const addButton=e.target.closest('[data-add-module]');if(addButton){layoutState.push(addButton.dataset.addModule);renderLayout();return}const removeButton=e.target.closest('[data-remove-index]');if(removeButton){layoutState.splice(Number(removeButton.dataset.removeIndex),1);renderLayout();return}const removeGallery=e.target.closest('[data-remove-gallery]');if(removeGallery){galleryFiles.splice(Number(removeGallery.dataset.removeGallery),1);renderGallery();return}const mediaCard=e.target.closest('[data-media-id]');if(mediaCard){const mediaId=Number(mediaCard.dataset.mediaId);selectedMedia.has(mediaId)?selectedMedia.delete(mediaId):selectedMedia.add(mediaId);syncSelectedMedia();}});
new Sortable(layoutList,{animation:150,handle:'.layout-handle',onEnd(){layoutState=[...layoutList.querySelectorAll('input[name="bo_cuc[]"]')].map(input=>input.value);renderLayout();}});
document.getElementById('applyTemplateBtn').addEventListener('click',()=>{const id=document.getElementById('templateSelect').value;if(!id||!templatePayload[id])return;const tpl=templatePayload[id];document.getElementById('ma_loai_su_kien').value=tpl.type||'';document.getElementById('dia_diem').value=tpl.location||'';document.getElementById('so_luong_toi_da').value=tpl.capacity||'';document.getElementById('diem_cong').value=tpl.points||'';quill.root.innerHTML='';if(tpl.content){quill.clipboard.dangerouslyPasteHTML(tpl.content)}document.getElementById('mo_ta_chi_tiet').value=quill.root.innerHTML;layoutState=tpl.layout?.length?[...tpl.layout]:['banner','header','info','description','gallery'];renderLayout();});
form.addEventListener('submit',async e=>{if(form.dataset.collisionChecked==='1')return;const start=document.getElementById('thoi_gian_bat_dau').value,end=document.getElementById('thoi_gian_ket_thuc').value,location=document.getElementById('dia_diem').value.trim();if(!start||!end||!location)return;e.preventDefault();submitBtn.disabled=true;submitBtn.innerHTML='<span class="spinner-border spinner-border-sm"></span> Dang kiem tra...';try{const res=await fetch('{{ route('admin.su-kien.check-collision') }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},body:JSON.stringify({thoi_gian_bat_dau:start,thoi_gian_ket_thuc:end,dia_diem:location})});const data=await res.json();if(data.has_collision){const detail=(data.conflicts||[]).map(item=>`- ${item.ten_su_kien} (${new Date(item.thoi_gian_bat_dau).toLocaleString('vi-VN')} - ${new Date(item.thoi_gian_ket_thuc).toLocaleString('vi-VN')})`).join('\n');if(!confirm(`Co su kien trung lich tai dia diem nay:\n\n${detail}\n\nBan van muon tiep tuc?`)){submitBtn.disabled=false;submitBtn.innerHTML='<i class="bi bi-check2-circle"></i> Tao su kien';return;}}form.dataset.collisionChecked='1';form.requestSubmit()}catch(error){form.dataset.collisionChecked='1';form.requestSubmit()}});
renderLayout(); syncSelectedMedia(); renderGallery();
</script>
@endsection
