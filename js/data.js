// API Functions
const API_BASE = 'api/';

// Fungsi untuk mengambil data penyewa
async function getPenyewa() {
    try {
        const response = await fetch(API_BASE + 'penyewa.php');
        return await response.json();
    } catch (error) {
        console.error('Error fetching penyewa:', error);
        return [];
    }
}

// Fungsi untuk menambah penyewa
async function addPenyewa(data) {
    try {
        const response = await fetch(API_BASE + 'penyewa.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        return await response.json();
    } catch (error) {
        console.error('Error adding penyewa:', error);
        return { success: false };
    }
}

// Fungsi untuk mengambil data gedung
async function getGedung() {
    try {
        const response = await fetch(API_BASE + 'gedung.php');
        return await response.json();
    } catch (error) {
        console.error('Error fetching gedung:', error);
        return [];
    }
}

// Fungsi untuk mengambil data booking
async function getBooking() {
    try {
        const response = await fetch(API_BASE + 'booking.php');
        return await response.json();
    } catch (error) {
        console.error('Error fetching booking:', error);
        return [];
    }
}