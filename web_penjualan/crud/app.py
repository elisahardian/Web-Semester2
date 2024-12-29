from flask import Flask, render_template, \
    request, redirect, url_for

import pymysql.cursors, os
import datetime
from flask import jsonify

application = Flask(__name__)

conn = cursor = None

#fungsi koneksi ke basis data
def openDb():
    global conn, cursor
    conn = pymysql.connect(db="db_penjualan", user="root", passwd="",host="localhost",port=3306,autocommit=True)
    cursor = conn.cursor()	

#fungsi menutup koneksi
def closeDb():
    global conn, cursor
    cursor.close()
    conn.close()

#fungsi view index() untuk menampilkan data dari basis data
@application.route('/')
def index():   
    openDb()
    container = []
    sql = "SELECT * FROM product ORDER BY kode DESC;"
    cursor.execute(sql)
    results = cursor.fetchall()
    for data in results:
        container.append(data)
    closeDb()
    return render_template('index.html', container=container,)


#fungsi membuat kode otomatis
def generate_kode():
    # mendefinisikan fungsi openDb(), cursor, dan closeDb() 
    openDb()

    current_year = datetime.datetime.now().year
    current_month = datetime.datetime.now().month
    
    # Mengambil empat digit terakhir dari tahun
    year_str = str(current_year).zfill(2)
    
    # Mengambil dua digit dari bulan
    current_month_str = str(current_month).zfill(2)

    # Membuat format kode tanpa nomor urut terlebih dahulu
    base_kode_without_number = f"P-{year_str}{current_month_str}"

    # Mencari kode terakhir dari database untuk mendapatkan nomor urut
    cursor.execute("SELECT kode FROM product WHERE kode LIKE %s ORDER BY kode DESC LIMIT 1", (f"{base_kode_without_number}%",))
    last_kode = cursor.fetchone()

    if last_kode:
        last_number = int(last_kode[0].split("-")[-1])  # Mengambil nomor urut terakhir
        next_number = last_number + 1
        # Membuat kode lengkap dengan nomor urut
        next_kode = f"P-{str(next_number).zfill(3)}"
    else:
        next_number = 1  # Jika belum ada data, mulai dari 1
        # Membuat kode lengkap dengan nomor urut
        next_kode = f"{base_kode_without_number}{str(next_number).zfill(3)}"
    
    closeDb()  # untuk menutup koneksi database 
    
    return next_kode

#fungsi untuk menyimpan lokasi foto
UPLOAD_FOLDER = '/web_penjualan/crud/static/foto/'
application.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

#fungsi view tambah() untuk membuat form tambah data
@application.route('/tambah', methods=['GET','POST'])
def tambah():
    generated_kode = generate_kode()  # Memanggil fungsi untuk mendapatkan kode otomatis
    
    if request.method == 'POST':
        kode = request.form['kode']
        nama = request.form['nama']
        varian = request.form['varian']
        expired = request.form['expired']
        stok = request.form['stok']
        beratkotor = request.form['beratkotor']
        harga = request.form['harga']
        foto = request.form['kode']
        

        # Pastikan direktori upload ada
        if not os.path.exists(UPLOAD_FOLDER):
            os.makedirs(UPLOAD_FOLDER)

        # Simpan foto dengan nama kode
        if 'foto' in request.files:
            foto = request.files['foto']
            if foto.filename != '':
                foto.save(os.path.join(application.config['UPLOAD_FOLDER'], f'{kode}.jpg'))

        openDb()
        sql = "INSERT INTO product (kode,nama,varian,expired,stok,beratkotor,harga, foto) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)"
        val = (kode,nama,varian,expired,stok,beratkotor,harga, foto)
        cursor.execute(sql, val)
        conn.commit()
        closeDb()
        return redirect(url_for('index'))        
    else:
        return render_template('tambah.html', kode=generated_kode)  # Mengirimkan kode otomatis ke template
    
#fungsi view edit() untuk form edit data
@application.route('/edit/<kode>', methods=['GET','POST'])
def edit(kode):
    openDb()
    cursor.execute('SELECT * FROM product WHERE kode=%s', (kode))
    data = cursor.fetchone()
    if request.method == 'POST':
        kode = request.form['kode']
        nama = request.form['nama']
        varian = request.form['varian']
        expired = request.form['expired']
        stok = request.form['stok']
        beratkotor = request.form['beratkotor']
        harga = request.form['harga']
        foto = request.form['kode']

        path_to_photo = os.path.join(application.root_path, '/web_penjualan/crud/static/foto', f'{kode}.jpg')
        if os.path.exists(path_to_photo):
            os.remove(path_to_photo)
        
        # Pastikan direktori upload ada
        if not os.path.exists(UPLOAD_FOLDER):
            os.makedirs(UPLOAD_FOLDER)


        # Simpan foto dengan nama kode
        if 'foto' in request.files:
            foto = request.files['foto']
            if foto.filename != '':
                foto.save(os.path.join(application.config['UPLOAD_FOLDER'], f"{kode}.jpg"))
        sql = "UPDATE product SET nama=%s, varian=%s, expired=%s, stok=%s, beratkotor=%s, harga=%s, foto=%s WHERE kode=%s"
        val = (nama, varian, expired,stok, beratkotor, harga, foto, kode)
        cursor.execute(sql, val)
        conn.commit()
        closeDb()
        return redirect(url_for('index'))
    else:
        closeDb()
        return render_template('edit.html', data=data)

#fungsi menghapus data
@application.route('/hapus/<kode>', methods=['GET','POST'])
def hapus(kode):
    openDb()
    cursor.execute('DELETE FROM product WHERE kode=%s', (kode,))
    # Hapus foto berdasarkan kode
    path_to_photo = os.path.join(application.root_path, '/web_penjualan/crud/static/foto', f'{kode}.jpg')
    if os.path.exists(path_to_photo):
        os.remove(path_to_photo)

    conn.commit()
    closeDb()
    return redirect(url_for('index'))

#fungsi cetak ke PDF
@application.route('/get_employee_data/<kode>', methods=['GET'])
def get_employee_data(kode):
    # Koneksi ke database
    connection = pymysql.connect(host='localhost',
                                 user='root',
                                 password='',  # Password Anda (jika ada)
                                 db='db_penjualan',
                                 charset='utf8mb4',
                                 cursorclass=pymysql.cursors.DictCursor)

    try:
        with connection.cursor() as cursor:
            # Query untuk mengambil data product berdasarkan kode
            sql = "SELECT * FROM product WHERE kode = %s"
            cursor.execute(sql, (kode,))
            employee_data = cursor.fetchone()  # Mengambil satu baris data product

            # Log untuk melihat apakah permintaan diterima dengan benar
            print("Menerima permintaan untuk kode:", kode)

            # Log untuk melihat data yang dikirim ke klien
            print("Data yang dikirim:", employee_data)

            return jsonify(employee_data)  # Mengembalikan data sebagai JSON

    except Exception as e:
        print("Error:", e)
        return jsonify({'error': 'Terjadi kesalahan saat mengambil data'}), 500

    finally:
        connection.close()  # Menutup koneksi database setelah selesai

#Program utama      
if __name__ == '__main__':
    application.run(debug=True)
