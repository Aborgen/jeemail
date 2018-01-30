import React, { Component } from 'react';

// Components
import Header               from '../../Components/Header/Header';
import DisplayBlock         from '../../Components/DisplayBlock/DisplayBlock';
import Footer               from '../../Components/Footer/Footer';
import Sidebar              from '../../Components/Sidebar/Sidebar';
import SubNav               from '../../Components/SubNav/SubNav';

class SettingsMenu extends Component {
    render() {
        const Fragment = React.Fragment;
        return (
            <Fragment>
                <Header />
                <SubNav
                    refresh = {this.refresh}
                    selectionOpt = {this.selectionOpt}
                    changeScene = {this.props.changeScene} />
                <Sidebar />
                <DisplayBlock blockType = {"settings"} />
                <Footer />
            </Fragment>
        );
    }
}

export default SettingsMenu;
