var userCollection = new OroApp.UserCollection();

userCollection.add([
    {id : 1, name : 'first'},
    {id : 2, name : 'second'}
]);

var columns = [
    {
        name: "id", // The key of the model attribute
        label: "ID", // The name to display in the header
        editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
        // Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
        cell: Backgrid.IntegerCell.extend({
            orderSeparator: ''
        })
    }, {
        name: "name",
        label: "Name",
        // The cell type can be a reference of a Backgrid.Cell subclass, any Backgrid.Cell subclass instances like *id* above, or a string
        cell: "string" // This is converted to "StringCell" and a corresponding class in the Backgrid package namespace is looked up
    }
];

// Initialize a new Grid instance
var grid = new OroApp.Datagrid({
    columns: columns,
    collection: userCollection
});

// Render the grid and attach the root to your HTML document
$("#backgrid").append(grid.render().$el);
