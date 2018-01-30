import React, { Component } from 'react';

// Components
import DisplayBlock         from '../../Components/DisplayBlock/DisplayBlock';
import Footer               from '../../Components/Footer/Footer';
import Header               from '../../Components/Header/Header';
import Sidebar              from '../../Components/Sidebar/Sidebar';
import SubNav               from '../../Components/SubNav/SubNav';

class Home extends Component {
    constructor() {
        super();
        this.state = {
            emails: [],
            selection: ""
        };

        this.refresh = this.refresh.bind(this);
        this.selectionOpt = this.selectionOpt.bind(this);
    }

    refresh(arr) {
        // this.setState({
        //     emails: arr
        // })
    }

    selectionOpt(checkStatus) {
        this.setState({
            selection: checkStatus
        })
    }

    render() {
        const Fragment = React.Fragment;
        return (
            <Fragment>
                <Header />
                <SubNav
                    changeScene = {this.props.changeScene}
                    refresh = {this.refresh}
                    selectionOpt = {this.selectionOpt} />
                <Sidebar />
                <DisplayBlock
                    blockType = {"email"}
                    refreshEmails = {this.state.emails}
                    selectionOpt = {this.state.selection} />
            </Fragment>
        );
    }
}

export default Home;
