// Fungsi untuk menampilkan greeting berdasarkan waktu
function displayGreeting() {
    const greetingElement = document.getElementById('greeting');
    if (greetingElement) {
        const hour = new Date().getHours();
        let greeting = '';
        
        if (hour < 12) {
            greeting = 'Selamat pagi';
        } else if (hour < 15) {
            greeting = 'Selamat siang';
        } else if (hour < 19) {
            greeting = 'Selamat sore';
        } else {
            greeting = 'Selamat malam';
        }
        
        greetingElement.textContent = greeting;
    }
}

// Fungsi untuk modal
function setupModals() {
    // Modal Tambah Stok/Penyewa/Gedung
    const addStockBtn = document.getElementById('addStockBtn');
    const addStockModal = document.getElementById('addStockModal');
    
    if (addStockBtn && addStockModal) {
        addStockBtn.addEventListener('click', function() {
            addStockModal.style.display = 'block';
        });
    }
    
    // Modal Tambah Booking
    const addBookingBtn = document.getElementById('addBookingBtn');
    const addBookingModal = document.getElementById('addBookingModal');
    
    if (addBookingBtn && addBookingModal) {
        addBookingBtn.addEventListener('click', function() {
            addBookingModal.style.display = 'block';
        });
    }
    
    // Tutup modal
    const closeButtons = document.querySelectorAll('.close');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                modal.style.display = 'none';
            }
        });
    });
    
    // Tutup modal saat klik di luar
    window.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
}









// Setup tampilan penyewa
function setupPenyewa() {
    const tableBody = document.getElementById('stockTableBody');
    
    if (tableBody) {
        // Tampilkan data awal
        displayPenyewaData();
        
        // Setup form tambah penyewa
        const addStockForm = document.getElementById('addStockForm');
        
        if (addStockForm) {
            addStockForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const nama = document.getElementById('namapenyewa').value;
                const telepon = document.getElementById('notelp').value;
                const email = document.getElementById('email').value;
                const alamat = document.getElementById('alamat').value;
                
                // Validasi
                if (!nama || !telepon || !email || !alamat) {
                    alert('Semua field harus diisi');
                    return;
                }
                
                // Tambahkan data baru ke database
                const result = await addPenyewa({
                    nama: nama,
                    telepon: telepon,
                    email: email,
                    alamat: alamat
                });
                
                if (result.success) {
                    // Perbarui tabel
                    displayPenyewaData();
                    
                    // Tutup modal dan reset form
                    document.getElementById('addStockModal').style.display = 'none';
                    addStockForm.reset();
                    
                    alert('Penyewa berhasil ditambahkan');
                } else {
                    alert('Gagal menambahkan penyewa');
                }
            });
        }
    }
}

// Fungsi untuk menampilkan data penyewa
async function displayPenyewaData() {
    const tableBody = document.getElementById('stockTableBody');
    
    if (tableBody) {
        tableBody.innerHTML = '<tr><td colspan="5">Loading...</td></tr>';
        
        const penyewaData = await getPenyewa();
        tableBody.innerHTML = '';
        
        penyewaData.forEach(item => {
            const row = document.createElement('tr');
            
            row.innerHTML = `
                <td>${item.id}</td>
                <td>${item.nama}</td>
                <td>${item.telepon}</td>
                <td>${item.email}</td>
                <td>${item.alamat}</td>
            `;
            
            tableBody.appendChild(row);
        });
    }
}

// Setup tombol aksi (edit dan hapus)
function setupActionButtons() {
    const editButtons = document.querySelectorAll('.edit-btn');
    const deleteButtons = document.querySelectorAll('.delete-btn');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const kode = this.getAttribute('data-kode');
            alert(`Fitur edit untuk kode ${kode} akan diimplementasikan`);
        });
    });
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const kode = this.getAttribute('data-kode');
            
            if (confirm(`Apakah Anda yakin ingin menghapus stok dengan kode ${kode}?`)) {
                // Hapus dari array dataBahanAjar
                const index = dataBahanAjar.findIndex(item => item.kode === kode);
                if (index !== -1) {
                    dataBahanAjar.splice(index, 1);
                    displayStokData();
                    alert('Stok berhasil dihapus');
                }
            }
        });
    });
}

// Setup logout
function setupLogout() {
    const logoutBtn = document.getElementById('logoutBtn');
    
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                window.location.href = 'index.html';
            }
        });
    }
}

// Setup registrasi modal
function setupRegistration() {
    const registerBtn = document.getElementById('registerBtn');
    const registerModal = document.getElementById('registerModal');
    const registerForm = document.getElementById('registerForm');
    
    if (registerBtn && registerModal) {
        registerBtn.addEventListener('click', function(e) {
            e.preventDefault();
            registerModal.style.display = 'block';
        });
    }
    
    if (registerForm) {
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('register.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert(result.message);
                    registerModal.style.display = 'none';
                    registerForm.reset();
                } else {
                    alert(result.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan sistem!');
            }
        });
    }
}

// Inisialisasi semua fungsi saat DOM siap
document.addEventListener('DOMContentLoaded', function() {
    displayGreeting();
    setupModals();
    setupRegistration();
    setupPenyewa();
    setupLogout();
});
// Fungsi untuk menampilkan data dummy di halaman tracking
function displayDummyDataInTracking() {
    const tableBody = document.getElementById('dummyTableBody');
    
    if (tableBody && dataPengiriman) {
        tableBody.innerHTML = '';
        
        dataPengiriman.forEach(item => {
            const row = document.createElement('tr');
            
            // Tentukan class status
            let statusClass = '';
            if (item.status === 'Dikirim') statusClass = 'status-dikirim';
            else if (item.status === 'Diproses') statusClass = 'status-diproses';
            else if (item.status === 'Selesai') statusClass = 'status-selesai';
            
            row.innerHTML = `
                <td><strong>${item.noDO}</strong></td>
                <td>${item.namaMahasiswa}</td>
                <td><span class="${statusClass}">${item.status}</span></td>
                <td class="progress-cell">
                    <div class="progress-mini">
                        <div class="progress-fill" style="width: ${item.progress}%"></div>
                    </div>
                    <span>${item.progress}%</span>
                </td>
                <td>${item.ekspedisi}</td>
                <td class="action-buttons">
                    <button class="copy-do-btn" data-do="${item.noDO}">Salin</button>
                    <button class="use-do-btn" data-do="${item.noDO}">Gunakan</button>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
        
        // Setup event listener untuk tombol
        setupDummyTableButtons();
    }
}

// Setup tombol di tabel dummy
function setupDummyTableButtons() {
    // Tombol "Salin"
    const copyButtons = document.querySelectorAll('.copy-do-btn');
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const doNumber = this.getAttribute('data-do');
            navigator.clipboard.writeText(doNumber).then(() => {
                alert(`Nomor DO ${doNumber} berhasil disalin!`);
            });
        });
    });
    
    // Tombol "Gunakan"
    const useButtons = document.querySelectorAll('.use-do-btn');
    useButtons.forEach(button => {
        button.addEventListener('click', function() {
            const doNumber = this.getAttribute('data-do');
            const doInput = document.getElementById('doNumber');
            
            if (doInput) {
                doInput.value = doNumber;
                doInput.focus();
                
                // Scroll ke form pencarian
                doInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    });
}

