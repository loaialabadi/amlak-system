{{-- users/_form.blade.php --}}
<div class="mb-3">
    <label class="form-label fw-bold">الاسم <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label fw-bold">البريد الإلكتروني <span class="text-danger">*</span></label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label fw-bold">كلمة المرور {{ $user ? '<small class="text-muted fw-normal">(اتركها فارغة للإبقاء على الحالية)</small>' : '<span class="text-danger">*</span>' }}</label>
    <input type="password" name="password" class="form-control" {{ $user ? '' : 'required' }} minlength="8">
</div>
<div class="mb-3">
    <label class="form-label fw-bold">تأكيد كلمة المرور</label>
    <input type="password" name="password_confirmation" class="form-control">
</div>
<div class="mb-3">
    <label class="form-label fw-bold">الدور <span class="text-danger">*</span></label>
    <select name="role" class="form-select" required>
        @foreach($roles as $key => $label)
            <option value="{{ $key }}" {{ old('role', $user->role ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
</div>
@if($user)
<div class="mb-3">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
               {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">الحساب نشط</label>
    </div>
</div>
@endif
