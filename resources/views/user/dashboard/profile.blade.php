@extends('user.layouts.app')

@section('content')
  <!-- PROFILE SETTINGS CARD FORM -->
  <div class="col-left" style="grid-column: 1 / -1;">

    @if(session('success'))
      <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid var(--success); color: var(--success); padding: 12px 20px; border-radius: 12px; margin-bottom: 20px; font-size: 14px;">
        ✓ {{ session('success') }}
      </div>
    @endif

    <style>
      .profile-card-container {
        padding: 16px;
      }
      .profile-header-wrap {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }
      .profile-header-wrap label[for="profile_image_input"] {
        margin: 0 auto;
      }
      .save-btn-container {
        width: 100%;
      }
      .save-btn-container button {
        width: 100%;
      }
      @media (min-width: 768px) {
        .profile-card-container {
          padding: 32px;
        }
        .profile-header-wrap {
          flex-direction: row;
          align-items: center;
          text-align: left;
        }
        .profile-header-wrap label[for="profile_image_input"] {
          margin: 0;
        }
        .profile-grid-cols {
          grid-template-columns: 1fr 1fr !important;
        }
        .save-btn-container {
          width: auto;
        }
        .save-btn-container button {
          width: auto;
        }
      }
    </style>

    <section class="site-panel-card profile-card-container" style="border-radius: 24px;">
      <div class="site-panel-header" style="border-bottom: 1px solid var(--surface-border); padding-bottom: 20px; margin-bottom: 24px;">
        <div class="site-icon" style="background: rgba(124, 111, 247, 0.08); border-color: rgba(124, 111, 247, 0.2); color: var(--primary); font-size: 20px;">👤</div>
        <div style="display: flex; flex-direction: column;">
          <span class="site-meta-title" style="font-size: 18px;">Profile & Security Settings</span>
          <span class="site-meta-sub">Update your account credentials and system profile</span>
        </div>
      </div>

      <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 24px;">
        @csrf

        <!-- Avatar Upload Section -->
        <div class="profile-header-wrap" style="display: flex; gap: 20px; background: rgba(255, 255, 255, 0.01); border: 1px solid var(--surface-border); padding: 16px; border-radius: 16px;">
          <div style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden; border: 2px solid var(--primary); background-color: var(--surface-light); flex-shrink: 0; position: relative;">
            <img id="profile_image_preview" src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $user->code }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
          </div>
          <div style="display: flex; flex-direction: column; gap: 8px; width: 100%; overflow: hidden;" class="profile-upload-actions">
            <span style="font-size: 13px; font-weight: 600; color: var(--white);">Profile Image</span>
            
            <label for="profile_image_input" style="background: rgba(124, 111, 247, 0.1); border: 1px dashed var(--primary); color: var(--primary); padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 12px; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; justify-content: center; transition: all 0.2s; width: fit-content;" onmouseover="this.style.background='rgba(124, 111, 247, 0.2)'" onmouseout="this.style.background='rgba(124, 111, 247, 0.1)'">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
              <span>Upload New Photo</span>
            </label>
            <input id="profile_image_input" type="file" name="profile_image" style="display: none;" accept="image/*">
            
            <span id="file_name_display" style="font-size: 11px; color: var(--success); display: none; word-break: break-all;"></span>
            <span style="font-size: 10px; color: var(--text-muted); line-height: 1.4;">Recommended: Square image, max 2MB (jpeg, png, jpg, webp)</span>
          </div>
        </div>

        <script>
          document.getElementById('profile_image_input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
              // Update file name text
              const fileNameDisplay = document.getElementById('file_name_display');
              fileNameDisplay.textContent = 'Selected: ' + file.name;
              fileNameDisplay.style.display = 'block';

              // Update image preview
              const reader = new FileReader();
              reader.onload = function(e) {
                document.getElementById('profile_image_preview').src = e.target.result;
              }
              reader.readAsDataURL(file);
            }
          });
        </script>

        <!-- Grid Fields -->
        <div class="profile-grid-cols" style="display: grid; grid-template-columns: 1fr; gap: 20px;">

          <!-- Name field -->
          <div style="display: flex; flex-direction: column; gap: 6px;">
            <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Full Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="background: var(--surface-light); border: 1px solid var(--surface-border); color: var(--white); font-family: var(--font-outfit); font-size: 14px; padding: 12px 16px; border-radius: 12px; outline: none; transition: border-color 0.25s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--surface-border)'">
            @error('name')
              <span style="font-size: 11px; color: var(--danger);">{{ $message }}</span>
            @enderror
          </div>

          <!-- Email field -->
          <div style="display: flex; flex-direction: column; gap: 6px;">
            <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Email Address</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required style="background: var(--surface-light); border: 1px solid var(--surface-border); color: var(--white); font-family: var(--font-outfit); font-size: 14px; padding: 12px 16px; border-radius: 12px; outline: none; transition: border-color 0.25s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--surface-border)'">
            @error('email')
              <span style="font-size: 11px; color: var(--danger);">{{ $message }}</span>
            @enderror
          </div>

          <!-- Mobile field -->
          <div style="display: flex; flex-direction: column; gap: 6px;">
            <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Mobile Number</label>
            <input type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}" style="background: var(--surface-light); border: 1px solid var(--surface-border); color: var(--white); font-family: var(--font-outfit); font-size: 14px; padding: 12px 16px; border-radius: 12px; outline: none; transition: border-color 0.25s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--surface-border)'">
            @error('mobile')
              <span style="font-size: 11px; color: var(--danger);">{{ $message }}</span>
            @enderror
          </div>

          <!-- Staff/Supervisor ID (Readonly) -->
          <div style="display: flex; flex-direction: column; gap: 6px;">
            <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Employee Code</label>
            <input type="text" readonly value="CODE: {{ $user->code }}" style="background: rgba(255,255,255,0.02); border: 1px solid var(--surface-border); color: var(--text-muted); font-family: var(--font-mono); font-size: 13px; padding: 12px 16px; border-radius: 12px; outline: none; cursor: not-allowed;">
          </div>
        </div>

        <!-- Current Address -->
        <div style="display: flex; flex-direction: column; gap: 6px;">
          <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Current Address</label>
          <textarea name="current_address" rows="3" style="background: var(--surface-light); border: 1px solid var(--surface-border); color: var(--white); font-family: var(--font-outfit); font-size: 14px; padding: 12px 16px; border-radius: 12px; outline: none; transition: border-color 0.25s; resize: vertical;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--surface-border)'">{{ old('current_address', $user->current_address) }}</textarea>
          @error('current_address')
            <span style="font-size: 11px; color: var(--danger);">{{ $message }}</span>
          @enderror
        </div>



        <!-- Submit Button -->
        <div class="save-btn-container" style="margin-top: 10px;">
          <button type="submit" style="background: linear-gradient(95deg, var(--primary) 0%, #a59ef9 100%); border: none; color: var(--white); font-family: var(--font-outfit); font-size: 14px; font-weight: 600; padding: 14px 28px; border-radius: 12px; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 14px var(--primary-glow);" onmouseover="this.style.opacity='0.95'; this.style.transform='translateY(-1px)'" onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)'">
            Save Changes
          </button>
        </div>
      </form>
    </section>

  </div>
@endsection
