<script>
    Vue.directive('select', {
        twoWay: true,
        bind: function (el, binding, vnode) {
            $(el).select2().on("select2:select", (e) => {
                el.dispatchEvent(new Event('change', { target: e.target }));
            });
        },
    });

    var app = new Vue({
        el: '#app',
        data: {
            api:{
                allProvinsi: "/provinsi/getAll",
                kotaByProvId: "/kota/getByProvId",
                kcmByKotaId: "/kecamatan/getByKotaId",
                klhByKcmId: "/kelurahan/getByKcmId",
                create: "/RumahTangga/store",
                update: "/RumahTangga/update",

            },
            dataProvinsi: [],
            dataKota: [],
            dataKecamatan: [],
            dataKelurahan: [],
            provinsi: {},
            kota: {},
            kecamatan: {},
            kelurahan: {},
            rumah_tangga: {},
            anggota_keluarga: [],
            formAnggota: {},
            dataHubungan: ['Suami', 'Istri', 'Anak'],
            dataJenisKelamin: ['Laki-laki', 'Perempuan'],
            kepala: {},
            mode: "",
            anggota_keluarga_delete: []

        },

        mounted(){
            this.mode = {!! json_encode($mode) !!};

            if(this.mode == 'edit') this.initData();
            this.getAllProvinsi();
        },

        methods: {
            initData(){
                this.rumah_tangga = {!! json_encode($data) !!};
                this.provinsi = {!! json_encode($wilayah['provinsi']) !!};
                this.kota = {!! json_encode($wilayah['kota']) !!};
                this.kecamatan = {!! json_encode($wilayah['kecamatan']) !!};
                this.kelurahan = {!! json_encode($wilayah['kelurahan']) !!};
                this.anggota_keluarga = {!! json_encode($anggota) !!};

                this.getKotaByProvId(this.rumah_tangga.provinsi_id);
                this.getKcmByKotaId(this.rumah_tangga.kabupaten_id);
                this.getKlhByKcmId(this.rumah_tangga.kecamatan_id);

                this.setKepala(this.rumah_tangga.nama_kepala_keluarga);
            },

            setKepala(nama){
                this.anggota_keluarga.forEach((anggota) => {
                    if(anggota.nama == nama) this.kepala = anggota;
                })
            },

            deleteAnggota(anggota, index){
                this.anggota_keluarga.splice(index, 1);

                if(this.mode == 'edit') this.anggota_keluarga_delete.push(anggota.id);
            },

            changeProvinsi(){
                this.dataKota = [];
                this.dataKecamatan = [];
                this.dataKelurahan = [];
                this.getKotaByProvId(this.provinsi.id);
            },

            changeKota(){
                this.dataKecamatan = [];
                this.dataKelurahan = [];
                this.getKcmByKotaId(this.kota.id);
            },

            changeKecamatan(){
                this.dataKelurahan = [];
                this.getKlhByKcmId(this.kecamatan.id);
            },

            async getAllProvinsi(){
                const response = await axios.get(this.api.allProvinsi);
                this.dataProvinsi = response.data.data;
            },

            async getKotaByProvId(provId){
                this.showLoading();
                const response = await axios.get(this.api.kotaByProvId + "?provinsiId="+provId);
                this.dataKota = response.data.data;
                Swal.close();
            },

            async getKcmByKotaId(kotaId){
                this.showLoading();
                const response = await axios.get(this.api.kcmByKotaId + "?kotaId="+kotaId);
                this.dataKecamatan = response.data.data;
                Swal.close();
            },

            async getKlhByKcmId(kcmId){
                this.showLoading();
                const response = await axios.get(this.api.klhByKcmId + "?kecamatanId="+kcmId);
                this.dataKelurahan = response.data.data;
                Swal.close();
            },

            addAnggota(){
                this.anggota_keluarga.push(this.formAnggota);
                this.formAnggota = {};
                this.$forceUpdate();
            },

            save(){
                Swal.fire({
                    title: 'Apakah anda yakin ?',
                    text: "",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya'
                    }).then(async (result) => {
                    if (result.isConfirmed) {

                        this.rumah_tangga.provinsi_id = this.provinsi.id;
                        this.rumah_tangga.kabupaten_id = this.kota.id;
                        this.rumah_tangga.kecamatan_id = this.kecamatan.id;
                        this.rumah_tangga.desa_id = this.kelurahan.id;
                        this.rumah_tangga.nama_kepala_keluarga = this.kepala.nama;

                        let data = {
                            rumahTangga: this.rumah_tangga,
                            anggotaKeluarga: this.anggota_keluarga
                        };

                        let url = this.api.create;
                        if(this.mode == 'edit'){
                            url = this.api.update;
                            data.anggotaKeluargaDelete = this.anggota_keluarga_delete
                        }

                        const response = await axios.post(url, data);

                        if(response.data.status == "S"){
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: response.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            window.location = "/";
                        }
                        else {
                            Swal.fire({
                                icon: 'error',
                                title: '',
                                text: response.data.message
                            });
                        }
                    }
                });
            },

            showLoading(){
                Swal.fire({
                    title: 'Mohon Tunggu !',
                    html: '',
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                });
            }
        },
    });


    $('#provinsi').select2({
        placeholder: "Pilih Provinsi",
        allowClear: true
    });
    $('#kota').select2({
        placeholder: "Pilih Kota/Kabupaten",
        allowClear: true
    });
    $('#kecamatan').select2({
        placeholder: "Pilih Kecamatan",
        allowClear: true
    });
    $('#kelurahan').select2({
        placeholder: "Pilih Desa",
        allowClear: true
    });
    $('#hubungan').select2({
        placeholder: "Pilih Hubungan Keluarga",
        allowClear: true
    });
    $('#jenis-kelamin').select2({
        placeholder: "Pilih Jenis Kelamin",
        allowClear: true
    });
    $('#kepala').select2({
        placeholder: "Pilih Kepala Keluarga",
        allowClear: true
    });
</script>
