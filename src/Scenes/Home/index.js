import React, { Component } from 'react';

// Components
import Header               from './components/Header/Header';
import Sidebar              from './components/Sidebar/Sidebar';
import EmailBlock           from './components/EmailBlock/EmailBlock';

import Logo                 from '../../Components/Logo/Logo';
import SearchForm           from '../../Components/SearchForm/SearchForm';
import Dropdown             from '../../Components/Dropdown/Dropdown';


class Home extends Component {

    render() {
        return (
            <div>
                <Header>
                    <Logo />
                    <div className="hur">
                        <div className="gur">
                            <Dropdown />
                            <Dropdown />
                            <Dropdown />
                        </div>
                    </div>
                    <SearchForm />
                </Header>
                <Sidebar />
                <EmailBlock />
            </div>
        );
    }
}

export default Home;
