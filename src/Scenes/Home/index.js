import React, { Component } from 'react';

// Components
import Header               from './components/Header/Header';
import Sidebar              from './components/Sidebar/Sidebar';
import EmailBlock           from './components/EmailBlock/EmailBlock';

class Home extends Component {

    render() {
        return (
            <div>
                <Header />
                <Sidebar />
                <EmailBlock />
            </div>
        );
    }

}

export default Home;
