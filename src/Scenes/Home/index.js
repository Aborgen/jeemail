import React, { Component } from 'react';

// Components
import EmailBlock           from '../../Components/EmailBlock/EmailBlock';
import Header               from '../../Components/Header/Header';
import Sidebar              from '../../Components/Sidebar/Sidebar';
import SubNav               from '../../Components/SubNav/SubNav';

class Home extends Component {

    render() {
        return (
            <div>
                <Header />
                <SubNav />
                <Sidebar />
                <EmailBlock />
            </div>
        );
    }
}

export default Home;
