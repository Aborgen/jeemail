import React, { Component, Fragment } from 'react';

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

    splitMemberProps(member) {
        const { icon, settings } = member;
        return {
            header: {
                name      : member.full_name,
                username  : member.username,
                email     : member.email,
                iconSmall : icon.icon_small,
                iconMedium: icon.icon_medium
            },
            body: member
        };
    }

    render() {
        const { changeScene, saveView, currentView, member } = this.props;
        const { emails, selection } = this.state;
        const { header, body }      = this.splitMemberProps(member);

        return (
            <Fragment>
                <Header member = { header } />
                <SubNav
                    changeScene  = {changeScene}
                    refresh      = {this.refresh}
                    selectionOpt = {this.selectionOpt} />
                <Sidebar saveView={saveView} currentView={currentView} />
                <div className="FOOgly">
                    <DisplayBlock
                        blockType     = {"email"}
                        refreshEmails = {emails}
                        selectionOpt  = {selection}
                        member        = { body } />
                    <Footer />
                </div>
            </Fragment>
        );
    }
}

export default Home;
