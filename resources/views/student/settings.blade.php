@extends('student.layouts.app')

@section('title', 'Settings - EduFocus')

@section('content')
<div class="space-y-6">
	<div class="mb-8">
		<h1 class="text-3xl font-bold text-gray-900">Settings</h1>
		<p class="text-gray-600 mt-2">Manage your profile and preferences</p>
	</div>

	<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
		<!-- Profile -->
		<div class="bg-white rounded-xl shadow-sm p-6">
			<h2 class="text-lg font-semibold text-gray-900 mb-4">Profile</h2>
			<div class="space-y-4">
				<div>
					<label class="block text-sm text-gray-700 mb-1">Name</label>
					<input id="prof-name" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="{{ $user->name ?? '' }}"/>
				</div>
				<div>
					<label class="block text-sm text-gray-700 mb-1">Grade / Year</label>
					<input id="prof-grade" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="{{ $pref->grade_year ?? '' }}"/>
				</div>
				<button onclick="saveProfile()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save Profile</button>
			</div>
		</div>

		<!-- Password -->
		<div class="bg-white rounded-xl shadow-sm p-6">
			<h2 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h2>
			<div class="space-y-4">
				<input id="pwd-current" type="password" placeholder="Current password" class="w-full border border-gray-300 rounded-lg px-3 py-2"/>
				<input id="pwd-new" type="password" placeholder="New password" class="w-full border border-gray-300 rounded-lg px-3 py-2"/>
				<input id="pwd-confirm" type="password" placeholder="Confirm new password" class="w-full border border-gray-300 rounded-lg px-3 py-2"/>
				<button onclick="changePassword()" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">Update Password</button>
			</div>
		</div>

		<!-- Notifications & Theme -->
		<div class="bg-white rounded-xl shadow-sm p-6">
			<h2 class="text-lg font-semibold text-gray-900 mb-4">Notifications & Theme</h2>
			<div class="space-y-4">
				<label class="inline-flex items-center space-x-2">
					<input id="notif-email" type="checkbox" class="rounded" {{ ($meta['notifications']['email'] ?? true) ? 'checked' : '' }}>
					<span>Email notifications</span>
				</label>
				<label class="inline-flex items-center space-x-2">
					<input id="notif-push" type="checkbox" class="rounded" {{ ($meta['notifications']['push'] ?? false) ? 'checked' : '' }}>
					<span>Push notifications</span>
				</label>
				<div>
					<label class="block text-sm text-gray-700 mb-1">Theme</label>
					<select id="theme-select" class="w-full border border-gray-300 rounded-lg px-3 py-2">
						<option value="light" {{ ($meta['theme'] ?? 'light')==='light'?'selected':'' }}>Light</option>
						<option value="dark" {{ ($meta['theme'] ?? 'light')==='dark'?'selected':'' }}>Dark</option>
						<option value="system" {{ ($meta['theme'] ?? 'light')==='system'?'selected':'' }}>System</option>
					</select>
				</div>
				<button onclick="savePreferences()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Save Preferences</button>
			</div>
		</div>

		<!-- Focus Defaults -->
		<div class="bg-white rounded-xl shadow-sm p-6">
			<h2 class="text-lg font-semibold text-gray-900 mb-4">Default Focus Settings</h2>
			<div class="space-y-4">
				<div>
					<label class="block text-sm text-gray-700 mb-1">Session Type</label>
					<input id="focus-type" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="{{ $meta['focus_defaults']['session_type'] ?? 'study' }}"/>
				</div>
				<div>
					<label class="block text-sm text-gray-700 mb-1">Planned Minutes</label>
					<input id="focus-mins" type="number" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="{{ $meta['focus_defaults']['planned_minutes'] ?? 25 }}"/>
				</div>
				<button onclick="saveFocusDefaults()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Save Focus Defaults</button>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
async function saveProfile(){
	const res = await fetch('/student/settings/profile', {
		method: 'POST',
		headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
		body: JSON.stringify({
			name: document.getElementById('prof-name').value,
			grade_year: document.getElementById('prof-grade').value,
		})
	});
	if (res.ok) window.showToast ? window.showToast('Profile saved','success') : alert('Profile saved');
}

async function changePassword(){
	const res = await fetch('/student/settings/password', {
		method: 'POST',
		headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
		body: JSON.stringify({
			current_password: document.getElementById('pwd-current').value,
			new_password: document.getElementById('pwd-new').value,
			new_password_confirmation: document.getElementById('pwd-confirm').value,
		})
	});
	if (res.ok) window.showToast ? window.showToast('Password updated','success') : alert('Password updated');
}

async function savePreferences(){
	const res = await fetch('/student/settings/preferences', {
		method: 'POST',
		headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
		body: JSON.stringify({
			notifications:{
				email: document.getElementById('notif-email').checked,
				push: document.getElementById('notif-push').checked,
			},
			theme: document.getElementById('theme-select').value,
		})
	});
	if (res.ok) window.showToast ? window.showToast('Preferences saved','success') : alert('Preferences saved');
}

async function saveFocusDefaults(){
	const res = await fetch('/student/settings/preferences', {
		method: 'POST',
		headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
		body: JSON.stringify({
			focus_defaults:{
				session_type: document.getElementById('focus-type').value,
				planned_minutes: parseInt(document.getElementById('focus-mins').value, 10) || 25,
			}
		})
	});
	if (res.ok) window.showToast ? window.showToast('Focus defaults saved','success') : alert('Focus defaults saved');
}
</script>
@endpush
