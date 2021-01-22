<?php
    include "js/repositorio.php"; 
?>
<div class="table-container">
    <div class="table-responsive" style="min-height: 115px; border: 1px solid #ddd; margin-bottom: 13px; overflow-x: auto;">
        <table id="tableSearchResult" class="table table-bordered table-striped table-condensed table-hover dataTable">
            <thead>
                <tr role="row">
                    <th class="text-left" style="min-width:30px;">Apelido</th>
                    <th class="text-left" style="min-width:35px;">Convênio de Saúde</th>
                     <th class="text-left" style="min-width:35px;">Seguro de Vida</th>
                      <th class="text-left" style="min-width:35px;">Ativo</th>
                   
                </tr>
            </thead>
            <tbody>
                <?php 
                
                    $apelidoFiltro = "";
                    $convenioFiltro = "";
                    $seguroFiltro = "";
                    $ativoFiltro = "";
                    $where = "WHERE (0 = 0)";
                     
                    if ($_GET["apelidoFiltro"] != "") {
                        $apelidoFiltro = $_GET["apelidoFiltro"];
                        $where=$where." and (CS.[apelido] like '%' + "."replace('".$apelidoFiltro."',' ','%') + "."'%')";
                    }
                    
                    if ($_GET["convenioFiltro"] != "") {
                        $convenioFiltro = $_GET["convenioFiltro"];
                        $where=$where." and (CS.[descricao] like '%' + "."replace('".$convenioFiltro."',' ','%') + "."'%')";
                    }
                    
                     if ($_GET["seguroFiltro"] != "") {
                        $seguroFiltro = $_GET["seguroFiltro"];
                        $where=$where." and (CS.[seguroVida] = ". $seguroFiltro . ")";
                    }
                    
                     if ($_GET["ativoFiltro"] != "") {
                        $ativoFiltro = $_GET["ativoFiltro"];
                         $where=$where." and (CS.[ativo] =" . $ativoFiltro . ")";
                    }
                    
                    $sql="SELECT CS.codigo, CS.apelido, CS.descricao, CS.seguroVida, CS.ativo FROM Ntl.convenioSaude CS ";
                    $sql=$sql.$where;
                    
                    $reposit = new reposit();                                       
                    $result=$reposit->RunQuery($sql);

                    foreach($result as $row) {
                        $id = (int) $row['codigo'];
                        $apelido = $row['apelido'];
                        $convenio = $row['descricao'];
                        $seguro = (int) $row['seguroVida'];
                        $ativo = (int) $row['ativo'];
                        
                        //Modifica os valores booleanos por Sim e Não.
                        //Seguro de Vida
                        if ($seguro==1){
                            $descricaoSeguro = "Sim";
                        }
                        else{
                            $descricaoSeguro = "Não";                            
                        }
                        //Ativo
                        if ($ativo==1){
                            $descricaoAtivo = "Sim";
                        }
                        else{
                            $descricaoAtivo = "Não";                            
                        }
                         
                        echo '<tr >';
                        echo '<td class="text-left"><a href="cadastro_convenioSaudeCadastro.php?codigo='.$id.'">'.$apelido.'</a></td>';
                        echo '<td class="text-left"> ' .$convenio.'</td>';
                        echo '<td class="text-left">'.$descricaoSeguro.'</td>';
                        echo '<td class="text-left">'.$descricaoAtivo.'</td>';
                        echo '</tr >';
                    }
                ?>               
            </tbody>
        </table>        
    </div>    
</div>    
<!-- PAGE RELATED PLUGIN(S) -->
<script src="js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="js/plugin/datatables/dataTables.colVis.min.js"></script>
<!--script src="js/plugin/datatables/dataTables.tableTools.min.js"></script-->
<script src="js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="js/plugin/datatable-responsive/datatables.responsive.min.js"></script>

<link rel="stylesheet" type="text/css" href="js/plugin/Buttons-1.5.2/css/buttons.dataTables.min.css"/>
 
<script type="text/javascript" src="js/plugin/JSZip-2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="js/plugin/pdfmake-0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="js/plugin/pdfmake-0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="js/plugin/Buttons-1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="js/plugin/Buttons-1.5.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="js/plugin/Buttons-1.5.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="js/plugin/Buttons-1.5.2/js/buttons.print.min.js"></script>


<script>
    $(document).ready(function () {
        var responsiveHelper_datatable_tabletools = undefined;

        var breakpointDefinition = {
            tablet: 1024,
            phone: 480
        };

        /* TABLETOOLS */
        $('#tableSearchResult').dataTable({

            // Tabletools options:
            //   https://datatables.net/extensions/tabletools/button_options
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'B'l'C>r>" +
                    "t" +
                    "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
            "oLanguage": {
                "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>',
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                //"sLengthMenu": "_MENU_ Resultados por página",
                "sLengthMenu": "_MENU_",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                }
            },
            "buttons": [
                //{extend: 'copy', className: 'btn btn-default'},
                //{extend: 'csv', className: 'btn btn-default'},
                {extend: 'excel', className: 'btn btn-default'},
                {extend: 'pdf', className: 'btn btn-default'},
                //{extend: 'print', className: 'btn btn-default'}
            ],
            "autoWidth": true,
            
            "preDrawCallback": function () {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_datatable_tabletools) {
                    responsiveHelper_datatable_tabletools = new ResponsiveDatatablesHelper($('#tableSearchResult'), breakpointDefinition);
                }
            },
            "rowCallback": function (nRow) {
                responsiveHelper_datatable_tabletools.createExpandIcon(nRow);
            },
            "drawCallback": function (oSettings) {
                responsiveHelper_datatable_tabletools.respond();
            }
        });

        /* END TABLETOOLS */
    });
</script>
