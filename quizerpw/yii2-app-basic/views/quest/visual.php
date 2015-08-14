<?
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Quest */

$this->title = Yii::t('app', 'Edit Quest Tree') . ': ' . $quest->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $quest->name, 'url' => ['view', 'id' => $quest->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Edit Quest Tree');
?>

<h1><?= Html::encode($this->title) ?></h1>

<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" href="/js/jsplumb.css" />
<link rel="stylesheet" href="/js/tree.css">
<script src="/js/jquery.ui.touch-punch-0.2.2.min.js"></script>
<script src="/js/jquery.jsPlumb-1.7.5-min.js"></script>

<input type="hidden" id="quest_id" value="<?= $quest->id ?>" />

<div id="main">
    <div class="tree-panel">
        <?=Html::a('', '#', ['class' => 'save-tree', 'onclick' => 'saveConnections(); return false;', 'title' => Yii::t('app', 'Save')]);?>&nbsp;&nbsp;
        <?=Html::a('', Url::toRoute(['quest/update', 'id' => $quest->id]), ['class' => 'update-quest', 'title' => Yii::t('app', 'Update Quest')]);?>&nbsp;&nbsp;
        <?=Html::a('', Url::toRoute(['node/create', 'quest_id' => $quest->id]), ['class' => 'add-node', 'title' => Yii::t('app', 'Create Node')]);?>
    </div>
    <!-- demo -->
    <div class="demo flowchart-demo" id="flowchart-demo">
        <?php
            $top = 0;
            $left = 0;
            $leftDef = 100;

            foreach ($nodes as $key => $node):
                if ($node->top > 0)
                    $top = $node->top;
                else
                    $top = 10;

                if ($node->left > 0)
                    $left = $node->left;
                else {
                    $left = $leftDef + 150;
                    $leftDef = $left;
                }
            ?>
                <div class="window" id="flowchartWindow<?= $node->id ?>" style="top: <?= $top ?>px; left: <?= $left; ?>px">
                    <br/><br/><br/><strong><?= $node->name ?></strong>
                    <?=Html::a('', Url::toRoute(['node/update', 'id' => $node->id]), ['class' => 'update-node', 'title' => Yii::t('app', 'Update Node')])?>
                    <?=Html::a('', Url::toRoute(['node/delete', 'id' => $node->id]), ['class' => 'delete-node', 'title' => Yii::t('app', 'Delete Node')])?>
                </div>
        <?endforeach?>
    </div>
    <!-- /demo -->
</div>
<div id="node-popup"></div>
<div id="node-popup"></div>
<script>
    $(document).ready(function() {
        $('.add-node').click(function() {
            var self = $(this);

            $.ajax({
                type: 'GET',
                url: self.attr('href'),
                success: function(response) {
                    $('#node-popup').html(response);
                    $('#node-popup').dialog({
                        modal: true,
                        width: 'auto',
                        closeText: 'Закрыть',
                        resizable: false,
                        buttons: {
                            'Ok': function() {
                                $.ajax({
                                    type: 'POST',
                                    url: self.attr('href'),
                                    data: $('.node-form form').serialize(),
                                    success: function(response) {
                                        if(response)
                                            $('#node-popup').html(response);
                                        else
                                            window.location.reload();
                                    },
                                    error: function(err) {
                                        alert('Ошибка сервера');
                                        console.log(err);
                                    }
                                });
                            }
                        }
                    });
                },
                error: function(err) {
                    alert('Ошибка сервера');
                    console.log(err);
                }
            });

            return false;
        });

        $('.update-node').click(function() {
            var self = $(this);

            $.ajax({
                type: 'GET',
                url: self.attr('href'),
                success: function(response) {
                    $('#node-popup').html(response);
                    $('#node-popup').dialog({
                        modal: true,
                        width: 'auto',
                        closeText: 'Закрыть',
                        resizable: false,
                        buttons: {
                            'Ok': function() {
                                $.ajax({
                                    type: 'POST',
                                    url: self.attr('href'),
                                    data: $('.node-form form').serialize(),
                                    success: function(response) {
                                        if(response)
                                            $('#node-popup').html(response);
                                        else
                                            window.location.reload();
                                    },
                                    error: function(err) {
                                        alert('Ошибка сервера');
                                        console.log(err);
                                    }
                                });
                            }
                        }
                    });
                },
                error: function(err) {
                    alert('Ошибка сервера');
                    console.log(err);
                }
            });

            return false;
        });

        $('.delete-node').click(function() {
            if(confirm('<?=Yii::t('app', 'Are you sure?')?>')) {
                var self = $(this);

                $.ajax({
                    type: 'POST',
                    url: self.attr('href'),
                    success: function (response) {
                        window.location.reload();
                    },
                    error: function (err) {
                        alert('Ошибка сервера');
                        console.log(err);
                    }
                });
            }

            return false;
        });
    });


    var instance;

    function saveConnections() {
        var connects = 'quest_id=' + $("#quest_id").val();

        /*instance.selectEndpoints().each(function(endpoint) {

         console.log("**", endpoint);

         })*/

        $('.window').each(function (index) {
            if ($(this).attr('id')) {
                connects += '&pos[' + $(this).attr('id') + '][left]=' + $(this).css('left');
                connects += '&pos[' + $(this).attr('id') + '][top]=' + $(this).css('top');
            }
        });

        $.each(instance.getAllConnections(), function (idx, connection) {
            var uuids = connection.getUuids();
            connects += '&connects[' + idx + '][src]=' + connection.sourceId;
            connects += '&connects[' + idx + '][trg]=' + connection.targetId;
            connects += '&connects[' + idx + '][uuid]=' + uuids[0];
        });

        $.ajax({
            type: "post",
            dataType: 'json',
            url: '/quest/save/',
            data: connects,
            success: function() {
                alert('Дерево успешно сохранено')
            },
            error: function(err) {
                alert('Ошибка сервера');
                console.log(err)
            }
        });
    }

    jsPlumb.ready(function () {

        instance = jsPlumb.getInstance({
            // default drag options
            DragOptions: {cursor: 'pointer', zIndex: 2000},
            // the overlays to decorate each connection with.  note that the label overlay uses a function to generate the label text; in this
            // case it returns the 'labelText' member that we set on each connection in the 'init' method below.
            ConnectionOverlays: [
                ["Arrow", {location: 1}],
                ["Label", {
                    location: 0.1,
                    id: "label",
                    cssClass: "aLabel"
                }]
            ],
            Container: "flowchart-demo"
        });

        var basicType = {
            connector: "StateMachine",
            paintStyle: {strokeStyle: "red", lineWidth: 4},
            hoverPaintStyle: {strokeStyle: "blue"},
            overlays: [
                "Arrow"
            ]
        };
        instance.registerConnectionType("basic", basicType);

        // this is the paint style for the connecting lines..
        var connectorPaintStyle = {
                lineWidth: 4,
                strokeStyle: "#61B7CF",
                joinstyle: "round",
                outlineColor: "white",
                outlineWidth: 2
            },
        // .. and this is the hover style.
            connectorHoverStyle = {
                lineWidth: 4,
                strokeStyle: "#216477",
                outlineWidth: 2,
                outlineColor: "white"
            },
            endpointHoverStyle = {
                fillStyle: "#216477",
                strokeStyle: "#216477"
            },
        // the definition of source endpoints (the small blue ones)
            sourceEndpoint = {
                endpoint: "Dot",
                paintStyle: {
                    strokeStyle: "#7AB02C",
                    fillStyle: "transparent",
                    radius: 7,
                    lineWidth: 3
                },
                isSource: true,
                connector: ["Flowchart", {stub: [40, 60], gap: 10, cornerRadius: 5, alwaysRespectStubs: true}],
                connectorStyle: connectorPaintStyle,
                hoverPaintStyle: endpointHoverStyle,
                connectorHoverStyle: connectorHoverStyle,
                dragOptions: {},
                overlays: [
                    ["Label", {
                        location: [0.5, 1.5],
                        label: "Drag",
                        cssClass: "endpointSourceLabel"
                    }]
                ]
            },
        // the definition of target endpoints (will appear when the user drags a connection)
            targetEndpoint = {
                endpoint: "Dot",
                paintStyle: {fillStyle: "#7AB02C", radius: 11},
                hoverPaintStyle: endpointHoverStyle,
                maxConnections: -1,
                dropOptions: {hoverClass: "hover", activeClass: "active"},
                isTarget: true,
                overlays: [
                    ["Label", {location: [0.5, -0.5], label: "Drop", cssClass: "endpointTargetLabel"}]
                ]
            },
            init = function (connection) {
                connection.getOverlay("label").setLabel(connection.sourceId.substring(15) + "-" + connection.targetId.substring(15));
            };

        var _addEndpoints = function (toId, sourceAnchors, targetAnchors) {
            for (var i = 0; i < sourceAnchors.length; i++) {
                var sourceUUID = toId + sourceAnchors[i];
                instance.addEndpoint("flowchart" + toId, sourceEndpoint, {
                    anchor: sourceAnchors[i], uuid: sourceUUID
                });
            }
            for (var j = 0; j < targetAnchors.length; j++) {
                var targetUUID = toId + targetAnchors[j];
                instance.addEndpoint("flowchart" + toId, targetEndpoint, {anchor: targetAnchors[j], uuid: targetUUID});
            }
        };

        // suspend drawing and initialise.
        instance.batch(function () {

            <?php foreach ($nodes as $key => $node) {?>
            _addEndpoints("Window<?=$node->id?>", ["RightMiddle", "TopCenter"], ["LeftMiddle"]);
            <?php } ?>

            // listen for new connections; initialise them the same way we initialise the connections at startup.
            instance.bind("connection", function (connInfo, originalEvent) {
                init(connInfo.connection);
            });

            // make all the window divs draggable
            instance.draggable(jsPlumb.getSelector(".flowchart-demo .window"), {grid: [20, 20]});
            // THIS DEMO ONLY USES getSelector FOR CONVENIENCE. Use your library's appropriate selector
            // method, or document.querySelectorAll:
            //jsPlumb.draggable(document.querySelectorAll(".window"), { grid: [20, 20] });

            // connect a few up
            <?php
            if ($chain) {
                foreach ($chain as $item) {
                    if  ($item['type'] == 'next') {
                         ?>
            instance.connect({
                uuids: ["Window<?=$item['src']?>RightMiddle", "Window<?=$item['trg']?>LeftMiddle"],
                editable: true
            });
            <?php
       }elseif  ($item['type'] == 'prev') {
            ?>
            instance.connect({
                uuids: ["Window<?=$item['src']?>TopCenter", "Window<?=$item['trg']?>LeftMiddle"],
                editable: true
            });
            <?php
       }
   }
}
?>
            //instance.connect({uuids: ["Window1RightMiddle", "Window2LeftMiddle"], editable: true});
            /*
             instance.connect({uuids: ["Window2LeftMiddle", "Window4LeftMiddle"], editable: true});
             instance.connect({uuids: ["Window4TopCenter", "Window4RightMiddle"], editable: true});
             instance.connect({uuids: ["Window3RightMiddle", "Window2RightMiddle"], editable: true});
             instance.connect({uuids: ["Window4BottomCenter", "Window1TopCenter"], editable: true});
             instance.connect({uuids: ["Window3BottomCenter", "Window1BottomCenter"], editable: true});*/
            //

            //
            // listen for clicks on connections, and offer to delete connections on click.
            //
            instance.bind("click", function (conn, originalEvent) {
                // if (confirm("Delete connection from " + conn.sourceId + " to " + conn.targetId + "?"))
                //   instance.detach(conn);
                conn.toggleType("basic");
            });

            instance.bind("connectionDrag", function (connection) {
                console.log("connection " + connection.id + " is being dragged. suspendedElement is ", connection.suspendedElement, " of type ", connection.suspendedElementType);
            });

            instance.bind("connectionDragStop", function (connection) {
                console.log("connection " + connection.id + " was dragged");
            });

            instance.bind("connectionMoved", function (params) {
                console.log("connection " + params.connection.id + " was moved");
            });
        });

        jsPlumb.fire("jsPlumbDemoLoaded", instance);

    });
</script>