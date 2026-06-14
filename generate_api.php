<?php

function write_file($path, $content) {
    file_put_contents(__DIR__ . "/" . $path, $content);
}

// 1. User Model
write_file("app/Models/User.php", <<<EOT
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected \$primaryKey = "id_user";

    protected \$fillable = [
        "nama",
        "username",
        "password",
        "email",
        "role",
    ];

    protected \$hidden = [
        "password",
    ];

    protected function casts(): array
    {
        return [
            "password" => "hashed",
        ];
    }
}
EOT
);

// 2. Aset Model
write_file("app/Models/Aset.php", <<<EOT
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    protected \$table = "aset";
    protected \$primaryKey = "id_aset";

    protected \$fillable = [
        "kode_aset",
        "nama_aset",
        "kategori",
        "merk",
        "lokasi",
        "kondisi",
        "status",
        "qr_code"
    ];

    public function peminjaman() {
        return \$this->hasMany(Peminjaman::class, "id_aset");
    }
}
EOT
);

// 3. Peminjaman Model
write_file("app/Models/Peminjaman.php", <<<EOT
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected \$table = "peminjaman";
    protected \$primaryKey = "id_peminjaman";

    protected \$fillable = [
        "id_user",
        "id_aset",
        "tanggal_pinjam",
        "rencana_kembali",
        "status",
        "catatan"
    ];

    public function user() {
        return \$this->belongsTo(User::class, "id_user");
    }

    public function aset() {
        return \$this->belongsTo(Aset::class, "id_aset");
    }

    public function pengembalian() {
        return \$this->hasOne(Pengembalian::class, "id_peminjaman");
    }
}
EOT
);

// 4. Pengembalian Model
write_file("app/Models/Pengembalian.php", <<<EOT
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected \$table = "pengembalian";
    protected \$primaryKey = "id_pengembalian";

    protected \$fillable = [
        "id_peminjaman",
        "tanggal_kembali",
        "kondisi_kembali",
        "catatan"
    ];

    public function peminjaman() {
        return \$this->belongsTo(Peminjaman::class, "id_peminjaman");
    }
}
EOT
);

// 5. LogAktivitas Model
write_file("app/Models/LogAktivitas.php", <<<EOT
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected \$table = "log_aktivitas";
    protected \$primaryKey = "id_log";

    protected \$fillable = [
        "id_user",
        "aktivitas",
        "waktu",
        "ip_address"
    ];

    public function user() {
        return \$this->belongsTo(User::class, "id_user");
    }
}
EOT
);

echo "Models generated.\n";

