<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    redirect('modules/auth/login.php');
}

$user_id = $_SESSION['user_id'];
$user = getUserById($user_id);
$message = '';
$error = '';

// Helper function to safely get value
function safeValue($value, $default = '') {
    return $value ?? $default;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = sanitize($_POST['full_name'] ?? '');
    $bio = sanitize($_POST['bio'] ?? '');
    $major = sanitize($_POST['major'] ?? '');
    $year_of_study = intval($_POST['year_of_study'] ?? 1);
    $university = sanitize($_POST['university'] ?? '');
    $location = sanitize($_POST['location'] ?? '');
    $interests = sanitize($_POST['interests'] ?? '');
    
    // Validation
    if (empty($full_name) || empty($major) || empty($university)) {
        $error = 'Please fill in all required fields.';
    } else {
        // Handle profile picture upload
        $profile_pic = $user['profile_pic'] ?? 'default-avatar.png';
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $filename = $_FILES['profile_pic']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $file_size = $_FILES['profile_pic']['size'];
            
            if ($file_size > 5242880) { // 5MB limit
                $error = 'Profile picture too large. Maximum 5MB allowed.';
            } elseif (in_array($ext, $allowed)) {
                $new_filename = 'user_' . $user_id . '.' . $ext;
                $upload_path = '../../assets/uploads/' . $new_filename;
                
                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $upload_path)) {
                    // Delete old profile picture if not default
                    if ($profile_pic != 'default-avatar.png' && file_exists('../../assets/uploads/' . $profile_pic)) {
                        unlink('../../assets/uploads/' . $profile_pic);
                    }
                    $profile_pic = $new_filename;
                }
            } else {
                $error = 'Invalid file type. Allowed: JPG, JPEG, PNG, GIF, WEBP';
            }
        }
        
        // Handle cover photo upload
        $cover_photo = $user['cover_photo'] ?? '';
        if (isset($_FILES['cover_photo']) && $_FILES['cover_photo']['error'] == 0 && !$error) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $filename = $_FILES['cover_photo']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $file_size = $_FILES['cover_photo']['size'];
            
            if ($file_size > 10485760) { // 10MB limit for cover
                $error = 'Cover photo too large. Maximum 10MB allowed.';
            } elseif (in_array($ext, $allowed)) {
                $new_filename = 'cover_' . $user_id . '_' . time() . '.' . $ext;
                $upload_path = '../../assets/uploads/' . $new_filename;
                
                if (move_uploaded_file($_FILES['cover_photo']['tmp_name'], $upload_path)) {
                    // Delete old cover photo
                    if (!empty($cover_photo) && file_exists('../../assets/uploads/' . $cover_photo)) {
                        unlink('../../assets/uploads/' . $cover_photo);
                    }
                    $cover_photo = $new_filename;
                }
            } else {
                $error = 'Invalid cover photo type. Allowed: JPG, JPEG, PNG, GIF, WEBP';
            }
        }
        
        if (!$error) {
            $stmt = $conn->prepare("UPDATE users SET full_name=?, bio=?, major=?, year_of_study=?, university=?, location=?, interests=?, profile_pic=?, cover_photo=? WHERE id=?");
            $stmt->bind_param("sssisssssi", $full_name, $bio, $major, $year_of_study, $university, $location, $interests, $profile_pic, $cover_photo, $user_id);
            
            if ($stmt->execute()) {
                $_SESSION['user_name'] = $full_name;
                $message = 'Profile updated successfully!';
                // Refresh user data
                $user = getUserById($user_id);
            } else {
                $error = 'Error updating profile. Please try again.';
            }
        }
    }
}
?>
<?php include_once '../../includes/header.php'; ?>

<style>
    /* Edit Profile Page Styles */
    .edit-profile-container {
        min-height: 100vh;
        background: #f9fafb;
        padding: 20px 16px 80px;
    }

    .edit-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .edit-header {
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .edit-header h4 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
    }

    .edit-header p {
        margin: 8px 0 0;
        font-size: 13px;
        opacity: 0.9;
    }

    .edit-body {
        padding: 24px;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .form-label i {
        margin-right: 6px;
        color: #6366f1;
    }

    .required::after {
        content: '*';
        color: #ef4444;
        margin-left: 4px;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.2s;
        background: white;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    /* Image Upload */
    .image-upload-container {
        text-align: center;
    }

    .image-preview {
        margin-top: 12px;
        position: relative;
        display: inline-block;
    }

    .preview-img {
        border-radius: 50%;
        width: 120px;
        height: 120px;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .cover-preview {
        width: 100%;
        max-height: 150px;
        object-fit: cover;
        border-radius: 12px;
        margin-top: 12px;
    }

    /* Alert Messages */
    .alert-success {
        background: #dcfce7;
        color: #166534;
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Action Buttons */
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .btn-save {
        flex: 1;
        padding: 12px 24px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        border: none;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-save:active {
        transform: scale(0.98);
    }

    .btn-cancel {
        padding: 12px 24px;
        background: #f3f4f6;
        color: #4b5563;
        border: none;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        transition: all 0.2s;
    }

    .btn-cancel:active {
        transform: scale(0.98);
        background: #e5e7eb;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .edit-body {
            padding: 20px;
        }
        
        .preview-img {
            width: 100px;
            height: 100px;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-cancel {
            text-align: center;
        }
    }
</style>

<div class="edit-profile-container">
    <div class="edit-card">
        <div class="edit-header">
            <h4><i class="bi bi-person-gear"></i> Edit Profile</h4>
            <p>Update your personal information and profile picture</p>
        </div>
        
        <div class="edit-body">
            <?php if($message): ?>
            <div class="alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
            <?php endif; ?>
            
            <?php if($error): ?>
            <div class="alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" id="editProfileForm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required">
                                <i class="bi bi-person"></i> Full Name
                            </label>
                            <input type="text" name="full_name" class="form-control" 
                                   value="<?php echo htmlspecialchars(safeValue($user['full_name'])); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-envelope"></i> Email
                            </label>
                            <input type="email" class="form-control" 
                                   value="<?php echo htmlspecialchars(safeValue($user['email'])); ?>" disabled>
                            <small class="text-muted">Email cannot be changed</small>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required">
                                <i class="bi bi-mortarboard"></i> Major/Department
                            </label>
                            <input type="text" name="major" class="form-control" 
                                   value="<?php echo htmlspecialchars(safeValue($user['major'])); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required">
                                <i class="bi bi-calendar"></i> Year of Study
                            </label>
                            <select name="year_of_study" class="form-select" required>
                                <option value="1" <?php echo (safeValue($user['year_of_study']) == 1) ? 'selected' : ''; ?>>1st Year</option>
                                <option value="2" <?php echo (safeValue($user['year_of_study']) == 2) ? 'selected' : ''; ?>>2nd Year</option>
                                <option value="3" <?php echo (safeValue($user['year_of_study']) == 3) ? 'selected' : ''; ?>>3rd Year</option>
                                <option value="4" <?php echo (safeValue($user['year_of_study']) == 4) ? 'selected' : ''; ?>>4th Year</option>
                                <option value="5" <?php echo (safeValue($user['year_of_study']) >= 5) ? 'selected' : ''; ?>>5th Year+</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required">
                                <i class="bi bi-building"></i> University
                            </label>
                            <input type="text" name="university" class="form-control" 
                                   value="<?php echo htmlspecialchars(safeValue($user['university'])); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-geo-alt"></i> Location
                            </label>
                            <input type="text" name="location" class="form-control" 
                                   value="<?php echo htmlspecialchars(safeValue($user['location'])); ?>" 
                                   placeholder="City, Country">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-file-text"></i> Bio
                    </label>
                    <textarea name="bio" class="form-control" rows="4" 
                              placeholder="Tell us about yourself..."><?php echo htmlspecialchars(safeValue($user['bio'])); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-tags"></i> Interests
                    </label>
                    <input type="text" name="interests" class="form-control" 
                           value="<?php echo htmlspecialchars(safeValue($user['interests'])); ?>" 
                           placeholder="Programming, Design, Sports, Music (comma separated)">
                    <small class="text-muted">Separate interests with commas</small>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group image-upload-container">
                            <label class="form-label">
                                <i class="bi bi-camera"></i> Profile Picture
                            </label>
                            <input type="file" name="profile_pic" class="form-control" accept="image/*" 
                                   onchange="previewImage(this, 'profilePreview')">
                            <div class="image-preview">
                                <img id="profilePreview" 
                                     src="<?php echo SITE_URL; ?>assets/uploads/<?php echo htmlspecialchars(safeValue($user['profile_pic'], 'default-avatar.png')); ?>" 
                                     class="preview-img" alt="Profile Preview">
                            </div>
                            <small class="text-muted d-block">Max 5MB. JPG, PNG, GIF, WEBP</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group image-upload-container">
                            <label class="form-label">
                                <i class="bi bi-image"></i> Cover Photo
                            </label>
                            <input type="file" name="cover_photo" class="form-control" accept="image/*" 
                                   onchange="previewImage(this, 'coverPreview')">
                            <div class="image-preview">
                                <img id="coverPreview" 
                                     src="<?php echo SITE_URL; ?>assets/uploads/<?php echo htmlspecialchars(safeValue($user['cover_photo'])); ?>" 
                                     class="cover-preview" alt="Cover Preview"
                                     style="<?php echo empty($user['cover_photo']) ? 'display: none;' : ''; ?>">
                            </div>
                            <small class="text-muted d-block">Max 10MB. Recommended size: 1200x400px</small>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        <i class="bi bi-check-lg"></i> Save Changes
                    </button>
                    <a href="view.php?id=<?php echo $user_id; ?>" class="btn-cancel">
                        <i class="bi bi-x-lg"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>

<script>
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const preview = document.getElementById(previewId);
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            if (previewId === 'coverPreview') {
                preview.style.display = 'block';
            }
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Form validation before submit
document.getElementById('editProfileForm')?.addEventListener('submit', function(e) {
    const requiredFields = ['full_name', 'major', 'university'];
    let hasError = false;
    
    requiredFields.forEach(field => {
        const input = document.querySelector(`[name="${field}"]`);
        if (input && !input.value.trim()) {
            input.style.borderColor = '#ef4444';
            hasError = true;
        } else if (input) {
            input.style.borderColor = '#e5e7eb';
        }
    });
    
    if (hasError) {
        e.preventDefault();
        showToast('Please fill in all required fields', 'error');
    }
});

// Show cover preview if exists
const coverPreview = document.getElementById('coverPreview');
if (coverPreview && coverPreview.src && coverPreview.src.includes('assets/uploads/') && !coverPreview.src.includes('default')) {
    coverPreview.style.display = 'block';
}

// Toast notification function
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    const colors = {
        success: '#10b981',
        error: '#ef4444',
        info: '#6366f1'
    };
    toast.style.cssText = `
        position: fixed;
        bottom: 80px;
        left: 16px;
        right: 16px;
        background: ${colors[type]};
        color: white;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 14px;
        text-align: center;
        z-index: 9999;
        animation: slideUp 0.3s ease;
    `;
    toast.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle'} me-2"></i>${message}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
