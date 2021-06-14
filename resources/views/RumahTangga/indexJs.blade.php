<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script>
    var app = new Vue({
        el: '#app',
        data: {
            api: {
                rumahTanggaDT: "/RumahTangga/getListDT"
            }
        },

        mounted(){
            this.getRumahTanggaDT();
        },

        methods: {
            getRumahTanggaDT(){
                let table = $('#table-rumah-tangga').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    order: [[ 1, "desc" ]],
                    ajax: {
                        url: this.api.rumahTanggaDT
                    },
                    columns: [
                        {
                            data: null,
                            sortable: false,
                            searchable: false,
                            class: 'text-center',
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {data: 'provinsi'},
                        {data: 'kota'},
                        {data: 'kecamatan'},
                        {data: 'kelurahan'},
                        {data: 'alamat'},
                        {data: 'nama_kepala_keluarga'},
                        {
                            data: 'null',
                            class: 'text-nowrap text-center',
                            render: function (data, type, row, meta) {
                                let btn = "<a href='/RumahTangga/edit/"+ row.id +"' class='btn btn-sm btn-warning'><span class='fa fa-pencil-alt'></span></a>";
                                btn += "<button onclick='deleteData(\"" + row.id + "\")' href='/RumahTangga/delete/"+ row.id +"' style='margin-left: 1%' class='btn btn-sm btn-danger'><span class='fa fa-trash'></span></button>";
                                return btn;
                            }
                        }
                    ]
                });
            }
        },
    });

    async function deleteData(id){
        const response = await axios.get("/RumahTangga/delete/" + id);
        if(response.data.status == "S") window.location = "/";
    }
</script>
