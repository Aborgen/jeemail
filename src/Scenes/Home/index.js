import React, { Component } from 'react';

// Components
import EmailBlock           from '../../Components/EmailBlock/EmailBlock';
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
        return (
            <div>
                <Header />
                <SubNav
                    refresh = {this.refresh}
                    selectionOpt = {this.selectionOpt} />
                <Sidebar />
                <EmailBlock
                    refreshEmails = {this.state.emails}
                    selectionOpt = {this.state.selection} />
            </div>
        );
    }
}

export default Home;
