/* ===== CLINIC APP - SHARED JAVASCRIPT ===== */

/* ----- Navigation ----- */
function toggleNav() {
    document.getElementById('navMenu').classList.toggle('open');
    document.querySelector('.nav-overlay').classList.toggle('active');
}

/* ----- Notifications ----- */

/* ----- Form Validation ----- */
function handlePatientTypeChange() {
    const type = document.querySelector('[name="patient_type"]');
    const gender = document.querySelector('[name="gender"]');
    if (!type || !gender) return;
    if (type.value === 'pregnant') {
        gender.value = 'female';
        gender.disabled = true;
    } else {
        gender.disabled = false;
    }
}

function validatePatientForm(e) {
    const typeEl = document.querySelector('[name="patient_type"]');
    const type = typeEl ? typeEl.value : '';
    const gender = document.querySelector('[name="gender"]');
    const dob = document.querySelector('[name="date_of_birth"]');
    const genderVal = gender ? gender.value : '';
    const dobVal = dob ? dob.value : '';

    if (type === 'pregnant' && genderVal !== 'female') {
        alert('فقط الإناث يمكن تسجيلهن كحوامل');
        e.preventDefault();
        return false;
    }
    if (type === 'child' && dobVal) {
        const today = new Date();
        const birth = new Date(dobVal);
        const maxBirth = new Date(today.getFullYear() - 12, today.getMonth(), today.getDate());
        if (birth <= maxBirth) {
            alert('عمر المريض يتجاوز 12 سنة، لا يمكن تسجيله كطفل');
            e.preventDefault();
            return false;
        }
    }
}

/* ----- Registration ----- */
function toggleRoleFields() {
    const role = document.getElementById('role');
    if (!role) return;
    const val = role.value;
    const pf = document.getElementById('patientFields');
    const df = document.getElementById('doctorFields');
    if (pf) pf.style.display = val === 'patient' ? 'block' : 'none';
    if (df) df.style.display = val === 'doctor' ? 'block' : 'none';
}

function toggleChronicDisease() {
    const pt = document.getElementById('patient_type');
    if (!pt) return;
    const f = document.getElementById('chronicDiseaseField');
    if (f) f.style.display = pt.value === 'chronic_disease' ? 'block' : 'none';
}

function calculateAge(birthDate) {
    if (!birthDate) return;
    const today = new Date();
    const birth = new Date(birthDate);
    let age = today.getFullYear() - birth.getFullYear();
    const md = today.getMonth() - birth.getMonth();
    if (md < 0 || (md === 0 && today.getDate() < birth.getDate())) age--;
    const el = document.getElementById('ageDisplay');
    const inp = document.getElementById('ageInput');
    if (el) el.textContent = 'العمر: ' + age + ' سنة';
    if (inp) inp.value = age;
}

function toggleChronic() {
    const el = document.getElementById('patientType');
    if (!el) return;
    const f = document.getElementById('chronicFields');
    if (f) f.style.display = el.value === 'chronic_disease' ? 'block' : 'none';
    handlePatientTypeChange();
}

/* ----- Appointment Actions ----- */
function confirmAppointment(id) {
    const el = document.getElementById('confirmModal');
    if (el) el.style.display = 'flex';
}
function closeModal() {
    const el = document.getElementById('confirmModal');
    if (el) el.style.display = 'none';
}
function editAppointment(id) {
    const el = document.getElementById('editModal');
    if (el) el.style.display = 'flex';
}
function closeEditModal() {
    const el = document.getElementById('editModal');
    if (el) el.style.display = 'none';
}
function completeAppointment(id) {
    if (confirm('هل أنت متأكد من إكمال هذا الموعد؟')) {
        alert('تم إكمال الموعد بنجاح');
    }
}

/* ----- Doctor Profile ----- */
function toggleBio() {
    const sel = document.getElementById('specialtySelect');
    const bio = document.getElementById('bioSection');
    if (sel && bio) {
        bio.style.display = sel.value && sel.value !== 'طب عام' ? 'block' : 'none';
    }
}

/* ----- Auto-init on DOM ready ----- */
document.addEventListener('DOMContentLoaded', function() {
    /* Registration page - URL params */
    const params = new URLSearchParams(window.location.search);
    const role = params.get('role');
    const type = params.get('type');
    const roleEl = document.getElementById('role');
    if (role && roleEl) {
        roleEl.value = role;
    }
    if (type) {
        var ptVal = type;
        if (type === 'chronic') ptVal = 'chronic_disease';
        const ptEl = document.getElementById('patient_type');
        if (ptEl) {
            ptEl.value = ptVal;
            const pg = document.getElementById('patientTypeGroup');
            if (pg) pg.style.display = 'none';
            var ht = document.createElement('input');
            ht.type = 'hidden';
            ht.name = 'patient_type';
            ht.value = ptVal;
            const pf = document.getElementById('patientFields');
            if (pf) pf.insertBefore(ht, pg);
        }
    }
    toggleRoleFields();
    toggleChronicDisease();
    handlePatientTypeChange();
    toggleChronic();
});
