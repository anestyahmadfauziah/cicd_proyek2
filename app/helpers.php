use Illuminate\Support\Facades\DB;

function cekAkses($permission)
{
    if (auth()->guard('superadmin')->check()) {
        return true;
    }

    if (auth()->guard('admin')->check()) {

        $data = DB::table('hak_akses')
            ->where('role', 'admin')
            ->first();

        $permissions = json_decode($data->permissions ?? '[]', true);

        return in_array($permission, $permissions);
    }

    return false;
}