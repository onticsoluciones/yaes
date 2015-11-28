var YaesScanner = React.createClass({

    getInitialState: function() {
        return {
            status: -2
        }
    },

    componentDidMount: function () {
        this.reload()
    },

    reload: function() {

        this.setState({
            status: -2
        });

        $.get(this.props.url, function (response) {
            this.setState({
                status: response.status
            });
        }.bind(this));

    },

    render: function () {

        return (
            <div>
                <li>
                    {this.props.scanner}
                    -
                    { (this.state.status === -2) ? "Checking" : "" }
                    { (this.state.status === -1) ? "Unknown" : "" }
                    { (this.state.status ===  1) ? "Safe" : "" }
                    { (this.state.status ===  2) ? "Vulnerable" : "" }
                </li>
                <input type="button" value="Reload" onClick={this.reload} />
            </div>

        );
    }

});

var YaesDetectedSoftware = React.createClass({

    getInitialState: function() {

        return {
            software: null,
            scanners: null
        }
    },

    componentWillMount: function() {
        this.componentWillReceiveProps(this.props);
    },

    componentWillReceiveProps: function(nextProps) {

        var url = "/ajax.php?action=identify&url=" + encodeURIComponent(nextProps.url);
        this.setState(this.getInitialState());

        $.get(url, function (response) {
            this.setState({
                software: response.software,
                scanners: response.scanners
            });
        }.bind(this));

    },

    render: function () {

        if(this.props.url === null) {
            return <div />;
        }

        if(this.state.software === null) {
            return <div>Loading...</div>
        }

        var liItems = [];

        for (var i = 0; i < this.state.scanners.length; i++) {
            var url = "/ajax.php?action=scan&url=" + encodeURIComponent(this.props.url) + "&software=" + this.state.software + "&scanner=" + this.state.scanners[i];
            liItems.push(
                <YaesScanner url={url} scanner={this.state.scanners[i]} />
            );
        }

        return (
            <div>
                <div>Detected software: {this.state.software}</div>
                <ul>{liItems}</ul>
            </div>
        );
    }
});

var Yaes = React.createClass({

    getInitialState: function () {
        return {
            url: null,
            selected_url: null
        };
    },

    onChange: function (e) {
        this.setState({
            url: e.target.value,
            selected_url: null
        });
    },

    handleSubmit: function (e) {
        e.preventDefault();
        this.setState({
            selected_url: this.state.url
        });
    },

    render: function () {
        return (
            <div>
                <form onSubmit={this.handleSubmit}>
                    <input onChange={this.onChange} value={this.state.url} placeholder="Enter the website URL"/>
                    <input type="submit" value="Scan" />
                    { (this.state.selected_url !== null) ? <YaesDetectedSoftware url={this.state.selected_url} /> : '' }
                </form>
            </div>
        );
    }

});

ReactDOM.render(<Yaes />, document.getElementById("contenido"));