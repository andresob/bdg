<html xmlns="http://www.w3.org/1999/xhtml">
<?php require_once 'init.php'; ?>

    <head>
        <meta http-equiv="Content-Type"     content="text/html; charset=utf-8" />
        <link rel='stylesheet' type='text/css' href='css/classes.css' media='screen'/>
        <link rel='stylesheet' type='text/css' href='css/layout.css' media='screen'/>
        <link rel='stylesheet' type='text/css' href='css/layers.css' media='screen'/>
        <link rel='stylesheet' type='text/css' href='css/table.css' media='screen'/>
        <link rel='stylesheet' type='text/css' href='js/redmond/jquery-ui.custom.css' media='screen'/>
        <script type="text/javascript" src="js/jquery-latest.min.js"></script>
        <script type="text/javascript" src="js/jqueryui.min.js"></script>
        <script type="text/javascript" src="js/base62.js"></script>
        <script type="text/javascript" src="js/functions.js"></script>
        <script type="text/javascript" src="js/submit.js"></script>
        <script type="text/javascript" src="js/recupera_consulta.js"></script>
        
        <script type="text/javascript">
            $(document).ready(function() {
                $( "#tabs" ).tabs({event: "mouseover"}).find( ".ui-tabs-nav" ).sortable({ axis: "x" });
                var availableTags = [<?php echo $tags; ?>];
                $( "#tcons" ).autocomplete({
                    minLength: 0,
                    source: function( request, response ) {
                            response( $.ui.autocomplete.filter(
                                    availableTags, extractLast( request.term ) ) );
                    },
                    select: function( event, ui ) {
                            var terms = split( this.value );
                            // remove the current input
                            terms.pop();
                            // add the selected item
                            terms.push( ui.item.value );
                            // add placeholder to get the comma-and-space at the end
                            terms.push( "" );
                            this.value = terms.join( " " );
                            return false;
                    }
                });
            });
        </script>
        <script type="text/javascript" src="js/tags.js"></script>
        <?php if(geografico){ ?>
        <!--<script type="text/javascript" src="js/raphael.js"></script>
        <script type="text/javascript" src="js/raphael_example.js"></script>-->
        <script type="text/javascript" src="plugins/jpicker/jpicker-1.1.6.min.js"></script>
        <script type="text/javascript" src="js/jPickerAction.js"></script>
        <link rel="stylesheet" media="screen" type="text/css" href="plugins/jpicker/css/jPicker-1.1.6.min.css" />
        <link rel="stylesheet" media="screen" type="text/css" href="css/colorselector.css" />
        <?php } ?>
        <script type="text/javascript" src="plugins/svgpan/SVGPan.js"></script>
    </head>
    <body>
        <div id="tudo">
            <div id="listlayers" >
                    <div class="scroll">
                        <div>
                            Opções
                            <div id="opcoes">
                                
                                <a href="?action=getlink" id="getlink"><img src='img/link.png' alt="link"/></a>
                                <?php if(geografico){ ?>
                                <a href="merge.php?action=merge" target="__blank" id="merge">
                                    <img src='img/merge.png'/>
                                </a>
                                <?php }?>
                            </div>
                        </div>
                        <ul id="sortable"> <?php if(isset($layers)) echo $layers; ?> </ul>
                    </div>
            </div>
            <div id="consulta">
                <style>
                    a.tablink{
                        font-family:Verdana, Arial, sans-serif;
                        font-size:9px;
                    }
                </style>
                <div id="tabs">
                    <ul>
                        <li><a class='tablink' href="#tabs-1">Resultado</a></li>
                        <?php if(geografico){?><li><a class='tablink' href="#tabs-2">Mapa</a></li><?php }?>
                        <li><a class='tablink' href="#tabs-3">Schema</a></li>
                        <li><a class='tablink' href="#tabs-4">Tutorial</a></li>
                    </ul>
                    <div id="tabs-1">
                        <div id="mainlayer" class="mainlayer border bg"><?php if(isset($result)) echo $result; ?></div>
                    </div>
                    <?php if(geografico){ ?>
                    <div id="tabs-2">
                        <div id="maps" class="mainlayer border bg"><?php if(isset($map)) echo $map; ?></div>
                    </div>
                    <?php } ?>
                    <div id="tabs-3">
                        <div id="schema" class="mainlayer border bg"><?php if(isset($schema)) echo $schema; ?></div>
                    </div>
                    <div id="tabs-4">
                        <div id="tutorial" class="mainlayer border bg"><?php require_once 'lib/tutorial.php'; ?></div>
                    </div>
                    
                    <div id="formulario" class="border bg">
                        <form action="lib/ajax/consulta.php" method="post" id="form">
                            <textarea name="consulta" id="tcons" class="border"><?php if(isset($first)) echo $first; ?></textarea>
                            <br/>
                            <input type="submit" value="consultar" id="button"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>