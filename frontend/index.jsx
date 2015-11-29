var IpAddress = React.createClass({

    render: function() {
        return (
            <div className="half-unit">
                <dtitle>IP Address</dtitle>
                <hr />
                <div className="clockcenter">
                    <h2>{this.props.ip_address}</h2>
                    <h0>{this.props.domain}</h0>
                </div>
            </div>
        );
    }
});

var Software = React.createClass({

    render: function() {
        return (
            <div className="half-unit">
                <dtitle>Detected software</dtitle>
                <div className="clockcenter">
                    <h1 style={{textTransform: "capitalize"}}>{this.props.software}</h1>
                    <img src="assets/img/magento.png" alt="" />
                </div>
            </div>
        );
    }
});

var YaesScanner = React.createClass({

    getInitialState: function() {
        return {
            status: -2
        }
    },

    componentDidMount: function () {
        this.reload();
    },

    componentWillReceiveProps: function(nextProps) {
        this.reloadWithProps(nextProps)
    },

    reload: function() {
        this.reloadWithProps(this.props);
    },

    reloadWithProps: function(props) {

        this.setState({
            status: -2
        });

        $.get(props.url, function (response) {
            this.setState({
                status: response.status
            });
        }.bind(this));

    },

    render: function () {

        return (
            <div className="col-sm-3 col-lg-3">
                <div className="half-unit">
                    <dtitle>
                        <span className="scanner-name">{this.props.scanner}</span>
                        <span className="scanner-reload">
                            <i className="fa fa-refresh scanner-reload" onClick={this.reload} />
                        </span>
                    </dtitle>
                    <hr />
                        <div className="cont">
                            <p>
                                <bold>
                                    { (this.state.status === -2) ? <i className="fa fa-spinner fa-spin fa-2x" /> : "" }
                                    { (this.state.status === -1) ? "Unknown" : "" }
                                    { (this.state.status ===  1) ? "Safe" : "" }
                                    { (this.state.status ===  2) ? "Vulnerable" : "" }
                                </bold>
                            </p>
                        </div>
                </div>
            </div>
        );
    }

});

var YaesScannerCollection = React.createClass({

    getInitialState: function() {
        return {
            scanners: [],
            urls: []
        }
    },

    render: function() {
        var items = [];
        var scanners = this.props.scanners;
        for(var i=0; i<scanners.length; i++) {
            items.push(<YaesScanner url={this.props.urls[i]} scanner={scanners[i]} />);
        }

        return (<div>{items}</div>);
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

            var newState = {
                software: response.software,
                ip_address: response.ip_address,
                domain: response.domain,
                scanners: []
            };

            if(response.scanners) {
                newState.scanners = response.scanners;
            }

            this.setState(newState);

        }.bind(this));

    },

    componentDidUpdate: function() {

        var ipBlock, softwareBlock, scannerCollection;

        if(this.props.url !== null)
        {
            if(this.state.software !== null)
            {
                var urls = [];
                for(var i = 0; i < this.state.scanners.length; i++) {
                    urls.push("/ajax.php?action=scan&url=" + encodeURIComponent(this.props.url) + "&software=" + this.state.software + "&scanner=" + this.state.scanners[i]);
                }

                ipBlock = ReactDOM.render(
                    <IpAddress ip_address={this.state.ip_address} domain={this.state.domain} />,
                    document.getElementById("ip_address"));

                softwareBlock = ReactDOM.render(
                    <Software software={this.state.software} />,
                    document.getElementById("software"));

                scannerCollection = ReactDOM.render(
                    <YaesScannerCollection scanners={this.state.scanners} urls={urls} />,
                    document.getElementById("vulns"));
            }
        }

        return (
            <div>
                { ipBlock }
                { softwareBlock }
                { scannerCollection}
            </div>
        );
    },

    render: function () {

        return (this.state.software === null ? <i className="fa fa-spinner fa-spin fa-3x" /> : <i />);
    }
});

var Yaes = React.createClass({

    getInitialState: function () {
        return {
            url: null,
            selected_url: null,
            spinner_is_visible: false
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
            selected_url: this.state.url,
        });
    },

    render: function () {
        return (
            <form onSubmit={this.handleSubmit} action="#get-in-touch" method="POST" id="contact">
                <input type="text" onChange={this.onChange} value={this.state.url} placeholder="http://" />
                {/* <div class="switch">
                    <input type="radio" class="switch-input" name="view" value="on" id="on" checked="" />
                    <label for="on" class="switch-label switch-label-off">On</label>
                    <input type="radio" class="switch-input" name="view" value="off" id="off" />
                    <label for="off" class="switch-label switch-label-on">Off</label>
                    <span class="switch-selection"></span>
                    <p><grey>Autodetect software</grey></p>
                </div>*/}
                <div>
                    <input type="submit" id="submit" name="submit" value="Scan" />
                </div>
                <div id="yaes-loading"></div>
                { (this.state.selected_url !== null) ? <YaesDetectedSoftware url={this.state.selected_url} /> : '' }
            </form>
        );
    }

});

ReactDOM.render(<Yaes />, document.getElementById("scanbox"));
