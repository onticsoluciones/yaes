var Ontic = {};
Ontic.Yaes = {};

Ontic.Yaes.ScannerList = React.createClass({
    render: function() {
        var createItem = function(itemText, index) {
            return <li key={index + itemText}>{itemText}</li>;
        };
        return <ul>{this.props.items.map(createItem)}</ul>;
    }
});

var Yaes = React.createClass({

    render: function() {
        return (
            <div>
                <input type="text" />
                <input type="button" value="Scan" />
            </div>
        );
    }

});

ReactDOM.render(<Yaes />, document.getElementById("contenido"));