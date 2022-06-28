<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Roles List
            <a href="{{ route('role.create') }}" class="pl-2 pr-2 text-sm"><i class="fa fa-file"></i> Create</a>
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 bg-white border-b border-gray-200">
                        Role List
                    </div>
                    
                    <div class="px-2 py-2">
                        @include('includes.tabulator_search')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            var getDataUrl = @json(route('role.master'));
            let table;                                              //  The Table
            let searchValue = "";                                   //  The Serach Parameter
            //  Get The Filter Value
            function dataFilter(element)
            {
                searchValue = element.value;
                table.setData(getDataUrl,{search:searchValue});
            }
            //  Startup Function
            document.addEventListener('DOMContentLoaded',function(){
                viewDataTable();                                                        //  Load DataTable
            })
            function viewDataTable()
            {
                //  Populate Tabulator
                table = new Tabulator("#tableData", {
                    autoResize:true,
                    responsiveLayout:"collapse",
                    layout:"fitData",    	
                    index:"id",                                 
                    placeholder:"No Data Available",            
                    pagination:true,                            
                    paginationMode:"remote",                    
                    sortMode:"remote",
                    filterMode:"remote",
                    paginationSize:20,                      
                    paginationSizeSelector:[10,25,50,100],  
                    ajaxParams: function(){
                        return {search:searchValue};
                    },
                    ajaxURL: getDataUrl,
                    ajaxContentType:"json", 
                    initialSort:[ {column:"id", dir:"desc"} ],
                    height:"100%",
                    // Columns
                    columns:[
                        // Master Data
                        {title:"Id", field:"id" , visible:true ,headerSortStartingDir:"asc" , responsive:0},
                        {title:"Name", field:"title" , visible:true ,headerSortStartingDir:"asc" , responsive:0},
                        {title:"Slug", field:"slug" , visible:true ,headerSortStartingDir:"asc" , responsive:0},
                        {title:"Description", field:"description" , visible:true ,headerSortStartingDir:"asc" , responsive:0},
                        {title:"Edit" , formatter:editIcon, align:"center",headerSort:false, responsive:2,
                            cellClick:function(e, cell){
                                 window.open(window.location + "/" + cell.getRow().getData().id + "/edit","_self" );
                            }
                        },
                    ],
                    // Extra Pagination Data for End Users
                    ajaxResponse:function(getDataUrl, params, response){
                        remaining = response.total;
                        let doc = document.getElementById("example-table-info");
                        doc.classList.add('font-weight-bold');
                        doc.innerText = `Displaying ${response.from} - ${response.to} out of ${remaining} records`;
                        return response.data;
                    },
                });
                
            }
           
        </script>


    </x-slot>
</x-app-layout>
