var YaesDetectedSoftware = React.createClass({

    getInitialState: function() {
        return {
            statuses: new Array(this.props.scanners.length)
        }
    },

    componentDidMount: function() {
        var self = this;
        for(var i=0; i<this.props.scanners.length; i++) {
            var url = "/ajax.php?action=scan&url=" + encodeURIComponent(this.props.url) + "&software=" + this.props.software + "&scanner=" + this.props.scanners[i];
            (function() {
                var j = i;
                $.get(url, function(response) {
                    var statuses = self.state.statuses;
                    statuses[j] = response.status;
                    self.setState({
                        statuses: statuses
                    });
                });
            })();
        }

    },

    render: function() {

        var liItems = [];

        for (var i=0; i<this.props.scanners.length; i++) {
            liItems.push(
                <li>
                    {this.props.scanners[i]}
                    -
                    { (this.state.statuses[i] === -1) ? "Unknown" : "" }
                    { (this.state.statuses[i] ===  1) ? "Safe" : "" }
                    { (this.state.statuses[i] ===  2) ? "Vulnerable" : "" }
                </li>);
        }

        return (
            <div>
                <div>Detected software: {this.props.software}</div>
                <ul>{liItems}</ul>
            </div>
        );
    }
});

var Yaes = React.createClass({

    getInitialState: function() {
        return {url: ''};
    },

    onChange: function(e) {
        this.setState({url: e.target.value});
    },

    handleClick: function() {
        var self = this;
        var url = "/ajax.php?action=identify&url=" + encodeURIComponent(this.state.url);
        $.get(url, function(response) {
            self.setState({
                showResults: true,
                software: response.software,
                scanners: response.scanners
            });
            console.log(response);
        })
    },

    render: function() {
        return (
            <div>
                <form onsubmit="{this.handleSubmit}">
                    <input onChange={this.onChange} value={this.state.url} placeholder="Enter the website URL" />
                    <input type="button" value="Scan" onClick={this.handleClick} />
                    { this.state.showResults ? <YaesDetectedSoftware
                        software={this.state.software}
                        scanners={this.state.scanners}
                        url={this.state.url}
                    /> : null }
                </form>
            </div>
        );
    }

});

ReactDOM.render(<Yaes />, document.getElementById("contenido"));