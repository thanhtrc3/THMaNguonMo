<?php /** @var ProductModel $product */ ?>
<?php include 'app/views/shares/header.php'; ?>

<style>
    body::before { content: ''; position: fixed; inset: 0; background-image: linear-gradient(rgba(71, 232, 208, 0.025) 1px, transparent 1px), linear-gradient(90deg, rgba(71, 232, 208, 0.025) 1px, transparent 1px); background-size: 40px 40px; pointer-events: none; z-index: 0; }
    .wrapper { width: 100%; max-width: 600px; margin: 2rem auto; position: relative; z-index: 1; animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both; }
    @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .header-bar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
    .breadcrumb-link { font-family: var(--mono); font-size: 0.72rem; color: var(--muted); text-decoration: none; letter-spacing: 0.08em; display: flex; align-items: center; gap: 0.5rem; transition: color 0.2s; }
    .breadcrumb-link:hover { color: var(--accent); text-decoration: none; } .breadcrumb-link::before { content: '←'; font-size: 1rem; }
    .panel { background: var(--surface); border: 1px solid var(--border); border-radius: 4px; overflow: hidden; }
    .panel-header { padding: 2rem 2.5rem 1.5rem; border-bottom: 1px solid var(--border); position: relative; }
    .panel-header::after { content: ''; position: absolute; bottom: -1px; left: 2.5rem; width: 3rem; height: 2px; background: var(--accent); }
    .panel-title { font-size: 1.75rem; font-weight: 800; letter-spacing: -0.03em; color: var(--text); line-height: 1; }
    .panel-subtitle { font-family: var(--mono); font-size: 0.72rem; color: var(--muted); margin-top: 0.5rem; letter-spacing: 0.04em; }
    .changed-dot { display: inline-block; width: 6px; height: 6px; background: var(--accent); border-radius: 50%; margin-left: 6px; vertical-align: middle; opacity: 0; transition: opacity 0.2s; }
    .field-input.dirty ~ .changed-dot, .field-textarea.dirty ~ .changed-dot { opacity: 1; }
    .panel-body { padding: 2rem 2.5rem 2.5rem; }
    
    .alert-error {
        background: rgba(255, 77, 77, 0.08);
        border: 1px solid rgba(255, 77, 77, 0.3);
        border-left: 3px solid var(--danger);
        border-radius: 3px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.75rem;
    }
    .alert-error ul { list-style: none; margin: 0; padding: 0; }
    .alert-error li {
        font-family: var(--mono);
        font-size: 0.75rem;
        color: #ff8080;
        padding: 0.2rem 0;
        letter-spacing: 0.02em;
    }
    .alert-error li::before { content: '✕  '; }
    
    .field { margin-bottom: 1.5rem; }
    .field-label { display: flex; align-items: center; justify-content: space-between; font-family: var(--mono); font-size: 0.68rem; letter-spacing: 0.12em; text-transform: uppercase; color: var(--muted); margin-bottom: 0.6rem; }
    .field-label .req { color: var(--accent); }
    .field-input, .field-textarea { width: 100%; background: var(--bg); border: 1px solid var(--border); border-radius: 3px; color: var(--text); font-family: var(--sans); font-size: 0.95rem; font-weight: 400; padding: 0.75rem 1rem; outline: none; transition: border-color 0.2s, box-shadow 0.2s; -webkit-appearance: none; appearance: none; }
    .field-input:focus, .field-textarea:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(71, 232, 208, 0.08); }
    .field-textarea { resize: vertical; min-height: 100px; }
    select.field-input {
        height: auto !important;
        line-height: 1.5 !important;
        padding: 0.75rem 2.5rem 0.75rem 1rem !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23e8ff47' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: calc(100% - 1rem) center !important;
        background-size: 10px !important;
    }

    .price-wrapper { display: flex; border: 1px solid var(--border); border-radius: 3px; overflow: hidden; transition: border-color 0.2s, box-shadow 0.2s; }
    .price-wrapper:focus-within { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(71, 232, 208, 0.08); }
    .price-prefix { background: #1d1d1d; border-right: 1px solid var(--border); padding: 0.75rem 1rem; font-family: var(--mono); font-size: 0.78rem; color: var(--accent); letter-spacing: 0.06em; display: flex; align-items: center; }
    .price-wrapper .field-input { border: none; border-radius: 0; box-shadow: none !important; } .price-wrapper .field-input:focus { border: none; }
    .actions { margin-top: 2rem; display: flex; flex-direction: column; gap: 0.75rem; }
    .btn-primary { width: 100%; background: var(--accent); color: #0d0d0d; border: none; border-radius: 3px; font-family: var(--sans); font-size: 0.9rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; padding: 0.9rem 1rem; cursor: pointer; transition: background 0.2s, transform 0.15s; }
    .btn-primary:hover { background: var(--accent-dim); transform: translateY(-1px); } .btn-primary:active { transform: translateY(0); }
    .btn-secondary { width: 100%; background: transparent; color: var(--muted); border: 1px solid var(--border); border-radius: 3px; font-family: var(--mono); font-size: 0.75rem; letter-spacing: 0.08em; padding: 0.8rem 1rem; text-align: center; text-decoration: none; display: block; transition: color 0.2s, border-color 0.2s; }
    .btn-secondary:hover { color: var(--text); border-color: #4a4a4a; text-decoration: none; }

    .img-preview {
        width: 80px;
        height: 80px;
        border-radius: 4px;
        object-fit: cover;
        background: var(--bg);
        border: 1px solid var(--border);
        margin-right: 1rem;
        flex-shrink: 0;
    }
    .file-upload-wrap {
        position: relative;
        background: var(--bg);
        border: 1px dashed var(--border);
        border-radius: 3px;
        padding: 1rem;
        text-align: center;
        transition: all 0.2s;
        cursor: pointer;
        flex-grow: 1;
    }
    .file-upload-wrap:hover { border-color: var(--accent); background: rgba(71, 232, 208, 0.02); }
    .file-upload-input { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
    .file-upload-text { font-family: var(--mono); font-size: 0.75rem; color: var(--muted); }
    .file-upload-text span { color: var(--accent); text-decoration: underline; }
    .upload-container { display: flex; align-items: center; }
</style>

<div class="wrapper">
    <div class="header-bar">
        <a href="<?php echo BASE_URL; ?>/Product/manage" class="breadcrumb-link">Quay lại danh sách</a>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h1 class="panel-title">Cập nhật sản phẩm</h1>
            <p class="panel-subtitle">// Chỉnh sửa thông tin sản phẩm</p>
        </div>

        <div class="panel-body">
            <?php if (!empty($errors)): ?>
                <div class="alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo BASE_URL; ?>/Product/update" enctype="multipart/form-data" onsubmit="return validateForm();">
                <input type="hidden" name="id" value="<?php echo $product->getID(); ?>">
                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($product->getImage(), ENT_QUOTES, 'UTF-8'); ?>">

                <div class="field">
                    <label class="field-label" for="image">Ảnh sản phẩm</label>
                    <div class="upload-container">
                        <?php 
                            $imgSrc = $product->getImage();
                            if ($imgSrc): 
                                if (!filter_var($imgSrc, FILTER_VALIDATE_URL)) {
                                    $imgSrc = ltrim($imgSrc, '/');
                                    if (strpos($imgSrc, 'webbanhang/') === 0) {
                                        $imgSrc = substr($imgSrc, 11);
                                    }
                                    $imgSrc = BASE_URL . '/' . ltrim($imgSrc, '/');
                                }
                        ?>
                            <img src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>" alt="Preview" class="img-preview">
                        <?php else: ?>
                            <div class="img-preview" style="display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:0.6rem;font-family:var(--mono);">CHƯA CÓ ẢNH</div>
                        <?php endif; ?>
                        
                        <div style="flex-grow: 1; display: flex; flex-direction: column; gap: 0.5rem;">
                            <div class="file-upload-wrap" style="width: 100%;">
                                <input type="file" class="file-upload-input form-control" id="image" name="image" accept="image/*">
                                <div class="file-upload-text" id="fileText">Đổi ảnh: Kéo thả hoặc <span>chọn file mới</span></div>
                            </div>
                            <input type="url" class="field-input" id="image_url" name="image_url" placeholder="Hoặc nhập đường dẫn ảnh từ internet..." value="<?php echo htmlspecialchars(filter_var($product->getImage(), FILTER_VALIDATE_URL) ? $product->getImage() : '', ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="field-label">Ảnh phụ hiện tại (Gallery)</label>
                    <div id="existing_sub_images" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <?php if (!empty($product->sub_images)): ?>
                            <?php foreach ($product->sub_images as $subImg): 
                                $subImgSrc = $subImg['image_path'];
                                if ($subImgSrc):
                                    if (!filter_var($subImgSrc, FILTER_VALIDATE_URL)) {
                                        $subImgSrc = ltrim($subImgSrc, '/');
                                        if (strpos($subImgSrc, 'webbanhang/') === 0) {
                                            $subImgSrc = substr($subImgSrc, 11);
                                        }
                                        $subImgSrc = BASE_URL . '/' . ltrim($subImgSrc, '/');
                                    }
                            ?>
                                <div class="sub-img-edit-wrap" data-id="<?php echo $subImg['id']; ?>" style="position: relative; width: 70px; height: 70px; border-radius: 4px; border: 1px solid var(--border); overflow: hidden; background: #080808;">
                                    <img src="<?php echo htmlspecialchars($subImgSrc, ENT_QUOTES, 'UTF-8'); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    <button type="button" class="btn-remove-sub" onclick="markSubImageForDeletion(<?php echo $subImg['id']; ?>, this)" style="position: absolute; top: 2px; right: 2px; background: rgba(255, 77, 77, 0.9); border: none; border-radius: 2px; color: #fff; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; cursor: pointer; transition: background 0.2s;">✕</button>
                                </div>
                            <?php endif; endforeach; ?>
                        <?php else: ?>
                            <div style="font-family: var(--mono); font-size: 0.75rem; color: var(--muted);">// Chưa có ảnh phụ nào</div>
                        <?php endif; ?>
                    </div>
                    <div id="deleted_sub_images_container"></div>
                </div>

                <div class="field">
                    <label class="field-label" for="sub_images">Tải thêm ảnh phụ mới</label>
                    <div class="file-upload-wrap">
                        <input type="file" class="file-upload-input form-control" id="sub_images" name="sub_images[]" accept="image/*" multiple>
                        <div class="file-upload-text" id="subFilesText">Kéo thả hoặc <span>chọn nhiều ảnh phụ...</span></div>
                    </div>
                    <div id="sub_images_preview" style="display: flex; gap: 0.5rem; margin-top: 1rem; flex-wrap: wrap;"></div>
                </div>

                <div class="field">
                    <label class="field-label">Hoặc nhập đường dẫn ảnh phụ mới từ internet</label>
                    <div id="sub_images_urls_container" style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <!-- Ô nhập URL động sẽ được tạo ở đây -->
                    </div>
                    <button type="button" class="btn-secondary" id="add_url_btn" style="margin-top: 0.5rem; width: auto; padding: 0.5rem 1rem; font-size: 0.75rem; font-family: var(--mono); text-transform: uppercase;">+ Thêm đường dẫn ảnh phụ</button>
                </div>

                <div class="field">
                    <label class="field-label" for="name">
                        Tên sản phẩm <span class="req">*</span>
                    </label>
                    <div class="field-wrap">
                        <input type="text" class="field-input form-control" id="name" name="name" value="<?php echo htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" required>
                        <span class="changed-dot"></span>
                    </div>
                </div>

                <div class="field">
                    <label class="field-label" for="category_id">
                        Danh mục <span class="req">*</span>
                    </label>
                    <select class="field-input form-control" id="category_id" name="category_id" required style="background-color: var(--bg);">
                        <?php $currentCatId = $product->category_id; ?>
                        <?php 
                            $categories = $categories ?? []; 
                            foreach ($categories as $cat): 
                                $catId = htmlspecialchars($cat->getID(), ENT_QUOTES, 'UTF-8');
                        ?>
                            <option value="<?php echo $catId; ?>" <?php echo $currentCatId == $cat->getID() ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat->getName(), ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field">
                    <label class="field-label" for="description">
                        Mô tả
                    </label>
                    <div class="field-wrap">
                        <textarea class="field-textarea form-control" id="description" name="description"><?php echo htmlspecialchars($product->getDescription(), ENT_QUOTES, 'UTF-8'); ?></textarea>
                        <span class="changed-dot"></span>
                    </div>
                </div>

                <div class="field">
                    <label class="field-label" for="price">
                        Đơn giá <span class="req">*</span>
                    </label>
                    <div class="price-wrapper">
                        <span class="price-prefix">VNĐ</span>
                        <input type="number" class="field-input form-control" id="price" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars($product->getPrice(), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    <a href="<?php echo BASE_URL; ?>/Product/manage" class="btn-secondary">Hủy bỏ</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function markSubImageForDeletion(id, element) {
        if (confirm('Bạn có chắc chắn muốn xóa ảnh phụ này?')) {
            const container = document.getElementById('deleted_sub_images_container');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_sub_images[]';
            input.value = id;
            container.appendChild(input);
            
            const wrapper = element.closest('.sub-img-edit-wrap');
            if (wrapper) {
                wrapper.style.transition = 'all 0.2s';
                wrapper.style.opacity = '0';
                wrapper.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    wrapper.remove();
                    const existingContainer = document.getElementById('existing_sub_images');
                    if (existingContainer.children.length === 0) {
                        existingContainer.innerHTML = '<div style="font-family: var(--mono); font-size: 0.75rem; color: var(--muted);">// Chưa có ảnh phụ nào</div>';
                    }
                }, 200);
            }
        }
    }

    function validateForm() {
        const name  = document.getElementById('name').value.trim();
        const cat = document.getElementById('category_id').value;
        const price = parseFloat(document.getElementById('price').value);
        const errs  = [];
        if (name.length < 10 || name.length > 100) errs.push('Tên sản phẩm phải từ 10 đến 100 ký tự.');
        if (!cat) errs.push('Vui lòng chọn danh mục.');
        if (isNaN(price) || price <= 0) errs.push('Giá phải là số dương lớn hơn 0.');
        if (errs.length > 0) { alert('Vui lòng kiểm tra lại:\n• ' + errs.join('\n• ')); return false; }
        return true;
    }

    document.addEventListener('DOMContentLoaded', () => {
        const inputs = document.querySelectorAll('.field-input, .field-textarea');
        inputs.forEach(el => {
            const orig = el.value;
            el.addEventListener('input', () => {
                el.classList.toggle('dirty', el.value !== orig);
            });
        });

        // Update file text
        const fileInput = document.getElementById('image');
        const fileText = document.getElementById('fileText');
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                fileText.innerHTML = `File mới: <span style="color:var(--text)">${e.target.files[0].name}</span>`;
            }
        });

        // Hiển thị tên file và preview khi chọn nhiều ảnh phụ mới
        const subFilesInput = document.getElementById('sub_images');
        const subFilesText = document.getElementById('subFilesText');
        const previewContainer = document.getElementById('sub_images_preview');

        subFilesInput.addEventListener('change', function(e) {
            previewContainer.innerHTML = '';
            const files = e.target.files;
            if (files.length > 0) {
                subFilesText.innerHTML = `Đã chọn: <span style="color:var(--text)">${files.length} ảnh phụ mới</span>`;
                
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const imgDiv = document.createElement('div');
                        imgDiv.style.position = 'relative';
                        imgDiv.style.width = '60px';
                        imgDiv.style.height = '60px';
                        imgDiv.style.borderRadius = '3px';
                        imgDiv.style.border = '1px solid var(--border)';
                        imgDiv.style.overflow = 'hidden';
                        imgDiv.style.background = '#080808';
                        imgDiv.style.display = 'flex';
                        imgDiv.style.alignItems = 'center';
                        imgDiv.style.justifyContent = 'center';

                        const img = document.createElement('img');
                        img.src = event.target.result;
                        img.style.width = '100%';
                        img.style.height = '100%';
                        img.style.objectFit = 'cover';

                        imgDiv.appendChild(img);
                        previewContainer.appendChild(imgDiv);
                    }
                    reader.readAsDataURL(file);
                });
            } else {
                subFilesText.innerHTML = `Kéo thả hoặc <span>chọn nhiều ảnh phụ...</span>`;
            }
        });

        // Xử lý thêm/xóa đường dẫn ảnh phụ động
        const urlContainer = document.getElementById('sub_images_urls_container');
        const addUrlBtn = document.getElementById('add_url_btn');

        function createUrlRow() {
            const row = document.createElement('div');
            row.className = 'sub-image-url-row';
            row.style.display = 'flex';
            row.style.gap = '0.5rem';
            row.style.alignItems = 'center';

            const input = document.createElement('input');
            input.type = 'url';
            input.className = 'field-input';
            input.name = 'sub_images_urls[]';
            input.placeholder = 'Nhập đường dẫn ảnh phụ từ internet...';
            input.autocomplete = 'off';
            input.style.flexGrow = '1';

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.style.background = 'rgba(255, 77, 77, 0.1)';
            removeBtn.style.border = '1px solid rgba(255, 77, 77, 0.3)';
            removeBtn.style.borderRadius = '3px';
            removeBtn.style.color = '#ff8080';
            removeBtn.style.width = '38px';
            removeBtn.style.height = '38px';
            removeBtn.style.display = 'flex';
            removeBtn.style.alignItems = 'center';
            removeBtn.style.justifyContent = 'center';
            removeBtn.style.cursor = 'pointer';
            removeBtn.style.transition = 'all 0.2s';
            removeBtn.innerHTML = '✕';
            
            removeBtn.addEventListener('mouseenter', () => {
                removeBtn.style.background = 'var(--danger)';
                removeBtn.style.color = '#fff';
            });
            removeBtn.addEventListener('mouseleave', () => {
                removeBtn.style.background = 'rgba(255, 77, 77, 0.1)';
                removeBtn.style.color = '#ff8080';
            });

            removeBtn.addEventListener('click', () => {
                row.remove();
            });

            row.appendChild(input);
            row.appendChild(removeBtn);
            return row;
        }

        addUrlBtn.addEventListener('click', () => {
            urlContainer.appendChild(createUrlRow());
        });
    });
</script>

<?php include 'app/views/shares/footer.php'; ?>
