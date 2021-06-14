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
                create: "/RumahTangga/store"

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
            kepala: {}

        },

        mounted(){
            this.getAllProvinsi();
        },

        methods: {
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
                        // console.log("asfasgas");
                        // showLoading();

                        console.log(this.rumah_tangga);

                        this.rumah_tangga.provinsi_id = this.provinsi.id;
                        this.rumah_tangga.kabupaten_id = this.kota.id;
                        this.rumah_tangga.kecamatan_id = this.kecamatan.id;
                        this.rumah_tangga.desa_id = this.kelurahan.id;
                        this.rumah_tangga.nama_kepala_keluarga = this.kepala.nama;

                        let data = {
                            rumahTangga: this.rumah_tangga,
                            anggotaKeluarga: this.anggota_keluarga
                        };

                        console.log(data);

                        let url = this.api.create;
                        if(this.mode == 'edit') url = this.api.update;

                        const response = await axios.post(url, data);
                        // Swal.close();

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
