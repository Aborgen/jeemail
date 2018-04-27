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
        const { changeScene, saveView, currentView } = this.props;
        const { emails, selection }    = this.state;
        const Fragment = React.Fragment;
        return (
            <Fragment>
                <Header />
                <SubNav
                    changeScene  = {changeScene}
                    refresh      = {this.refresh}
                    selectionOpt = {this.selectionOpt} />
                <Sidebar saveView={saveView} currentView={currentView} />
                <div className="FOOgly">
                    <DisplayBlock
                        blockType     = {"email"}
                        refreshEmails = {emails}
                        selectionOpt  = {selection} />
                    <Footer />
                </div>
            </Fragment>
        );
    }
}

export default Home;
