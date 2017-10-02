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
            emails: []
        };
    }

    refresh(arr) {
        // this.setState({
        //     emails: arr
        // })
    }


    render() {
        return (
            <div>
                <Header />
                <SubNav refresh = {this.refresh.bind(this)} />
                <Sidebar />
                <EmailBlock refreshEmails = {this.state.emails} />
            </div>
        );
    }
}

export default Home;
