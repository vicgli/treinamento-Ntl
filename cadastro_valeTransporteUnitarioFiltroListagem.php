<?php
include "js/repositorio.php";
?>
<div class="table-container">
    <div class="table-responsive" style="min-height: 115px; border: 1px solid #ddd; margin-bottom: 13px; overflow-x: auto;">
        <table id="tableSearchResult" class="table table-bordered table-striped table-condensed table-hover dataTable">
            <thead>
                <tr role="row">
                    <th class="text-left" style="min-width:30px;">Transporte Unitário</th>
                    <th class="text-left" style="min-width:35px;">Valor</th>
                    <th class="text-left" style="min-width:35px;">UF</th>
                    <th class="text-left" style="min-width:35px;">Ativo</th>
                </tr>
            </thead>
            <tbody>
                <?php

                //Variáveis do filtro:
                $descricaoFiltro = "";
                $valorMinimo = "";
                $valorMaximo = "";
                $unidadeFederacao = "";
                $ativo = "";

                $where = "WHERE (0 = 0)";

                $sql = "SELECT VTU.[codigo], VTU.[descricao], VTU.[valorUnitario], VTU.[unidadeFederacao], VTU.[ativo]"
                    . " FROM [Ntl].[valeTransporteUnitario] VTU ";

                //Condições de pesquisa: 
                if ($_GET["descricaoFiltro"] != "") {
                    $descricaoFiltro = $_GET["descricaoFiltro"];
                    $where = $where . " AND (VTU.[descricao] like '%' + " . "replace('" . $descricaoFiltro . "',' ','%') + " . "'%')";
                }

                if (+$_GET["valorMinimo"] > 0) {
                    $valorMinimo = +$_GET["valorMinimo"];
                    $where =  $where . " AND VTU.[valorUnitario] >= " . $valorMinimo;
                }

                if (+$_GET["valorMaximo"] > 0) {
                    $valorMaximo = +$_GET["valorMaximo"];
                    $where =  $where . " AND VTU.[valorUnitario] <= " . $valorMaximo;
                }

                if ($_GET["unidadeFederacao"] != "") {
                    $unidadeFederacao = $_GET["unidadeFederacao"];
                    $where = $where . " AND VTU.[unidadeFederacao] = '" . $unidadeFederacao . "'";
                }

                if ($_GET["ativo"] != "") {
                    $ativo = $_GET["ativo"];
                    $where = $where . " AND VTU.[ativo] = '" . $ativo . "'";
                }

                $sql = $sql . $where;
                $reposit = new reposit();
                $result = $reposit->RunQuery($sql);

                while (($row = odbc_fetch_array($result))) {
                    $id = +$row['codigo'];
                    $descricao =  mb_convert_encoding($row['descricao'], 'UTF-8', 'HTML-ENTITIES');
                    $valorUnitario = mb_convert_encoding($row['valorUnitario'], 'UTF-8', 'HTML-ENTITIES');
                    $uf = mb_convert_encoding($row['unidadeFederacao'], 'UTF-8', 'HTML-ENTITIES');
                    $ativo = +$row['ativo'];
                    $descricaoAtivo = "";

                    if ($ativo == 1) {
                        $descricaoAtivo = "Sim";
                    } else {
                        $descricaoAtivo = "Não";
                    }

                    echo '<tr >';
                    echo '<td class="text-left"><a href="cadastro_valeTransporteUnitarioCadastro.php?id=' . $id . '">' . $descricao . '</a></td>';
                    echo '<td class="text-right"> R$ ' . number_format(round($valorUnitario, 2), 2, ',', '.') . '</td>';
                    echo '<td class="text-left">' . $uf . '</td>';
                    echo '<td class="text-left">' . $descricaoAtivo . '</td>';
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
<script src="js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
<script>
    $(document).ready(function() {

        var responsiveHelper_dt_basic = undefined;
        var responsiveHelper_datatable_fixed_column = undefined;
        var responsiveHelper_datatable_col_reorder = undefined;
        var responsiveHelper_datatable_tabletools = undefined;

        var breakpointDefinition = {
            tablet: 1024,
            phone: 480
        };

        /* TABLETOOLS */
        $('#tableSearchResult').dataTable({
            // Tabletools options: 
            //   https://datatables.net/extensions/tabletools/button_options
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'T>r>" +
                "t" +
                "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
            "oLanguage": {
                "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>',
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sLengthMenu": "_MENU_ Resultados por página",
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
            "oTableTools": {
                "aButtons": ["copy", "csv", "xls", {
                        "sExtends": "pdf",
                        "sTitle": "SmartAdmin_PDF",
                        "sPdfMessage": "SmartAdmin PDF Export",
                        "sPdfSize": "letter"
                    },
                    {
                        "sExtends": "print",
                        "sMessage": "Generated by SmartAdmin <i>(press Esc to close)</i>"
                    }
                ],
                "sSwfPath": "js/plugin/datatables/swf/copy_csv_xls_pdf.swf"
            },
            "autoWidth": true,
            "preDrawCallback": function() {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_datatable_tabletools) {
                    responsiveHelper_datatable_tabletools = new ResponsiveDatatablesHelper($('#tableSearchResult'), breakpointDefinition);
                }
            },
            "rowCallback": function(nRow) {
                responsiveHelper_datatable_tabletools.createExpandIcon(nRow);
            },
            "drawCallback": function(oSettings) {
                responsiveHelper_datatable_tabletools.respond();
            }
        });

    });
</script>