import React, { Component, Fragment } from 'react';
import PropTypes                      from 'prop-types';

import DisplayBlock         from '../../Components/DisplayBlock/DisplayBlock';
import Footer               from '../../Components/Footer/Footer';
import Header               from '../../Components/Header/Header';
import Sidebar              from '../../Components/Sidebar/Sidebar';
import SubNav               from '../../Components/SubNav/SubNav';

class Home extends Component {
    constructor() {
        super();
        this.state = {
            emailsz: [],
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
        const { changeScene, setView, getView, emails } = this.props;
        const { emailsz, selection } = this.state;
        console.log(emails);
        return (
            <Fragment>
                <Header />
                <SubNav
                    changeScene  = { changeScene }
                    refresh      = { this.refresh }
                    selectionOpt = { this.selectionOpt } />
                <Sidebar setView={ setView } getView={ getView } />
                <div className="displayContainer">
                    <DisplayBlock
                        blockType     = { "email" }
                        refreshEmails = { emailsz }
                        selectionOpt  = { selection }
                        emails        = { emails.data.emails.received } />
                    <Footer />
                </div>
            </Fragment>
        );
    }
}

export default Home;

Home.propTypes = {
    changeScene: PropTypes.func.isRequired,
    setView: PropTypes.func.isRequired,
    getView: PropTypes.func.isRequired,
    emails: PropTypes.array.isRequired
}
