<div class="row">
  <div class="col-xs-12 col-md-6 col-lg-6">
    <div class="form-group">
      <label>Username</label>
      <input type="text" name="username" class="form-control" required="">
      <div class="invalid-feedback">
        Username ?
      </div>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-md-6 col-lg-6">
    <div class="form-group">
      <label>Password</label>
      <div class="input-group">
        <input type="password" name="password" class="form-control" required="">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-eye" style="cursor:pointer" id="showhide"></i>
          </div>
        </div>
        <div class="invalid-feedback">
          Password ?
        </div>
        <div class="valid-feedback">
          Terisi!
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xs-2 col-md-6 col-lg-6">
    <div class="form-group">
      <label>Nama</label>
      <input type="text" name="nama" class="form-control" required="">
      <div class="invalid-feedback">
        Nama Pegawai ?
      </div>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
  <div class="col-xs-2 col-md-6 col-lg-6">
    <div class="form-group">
      <label>Telepon</label>
      <input type="number" name="telp" class="form-control" required="">
      <div class="invalid-feedback">
        Telepon ?
      </div>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
</div>
<div class="form-group">
  <label>Alamat</label>
  <textarea name="alamat" class="form-control" required=""></textarea>
  <div class="invalid-feedback">
    Alamat ?
  </div>
  <div class="valid-feedback">
    Terisi!
  </div>
</div>
<div class="form-group">
  <label>Jabatan</label>
  <select name="jabatan" class="form-control">
    <option value="admin">Admin</option>
    <option value="kepsek">Kepala Sekolah</option>
  </select>
  <div class="valid-feedback">
    Terisi!
  </div>
</div>