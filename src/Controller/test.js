var liste = [
    "Draggable",
    "Droppable",
    "Resizable",
    "Selectable",
    "Sortable"
];

$('#recherche').autocomplete({
    source : 'Model/MenuModel.php'
});