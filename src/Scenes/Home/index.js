import React, { Component } from 'react';

// Components
import Header               from '../../Components/Header/Header';
import SubNav               from '../../Components/SubNav/SubNav';
import Sidebar              from '../../Components/Sidebar/Sidebar';
import EmailBlock           from '../../Components/EmailBlock/EmailBlock';

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
