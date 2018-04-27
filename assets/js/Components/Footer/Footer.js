import React, { Component } from 'react';

class Footer extends Component {

    render() {
        return (
            <div className="footer">
                <div className="footerContent">
                    <span className="footerPiece">
                        <div className="footer__block">
                            <p>1.3 GB (8%) of 15 GB used</p>
                        </div>
                        <div className="footer__block">
                            <a href="">Manage</a>
                        </div>
                    </span>
                    <span className="footerPiece">
                        <div className="footer__inline">
                            <a href="">Terms</a>
                        </div>
                        <div className="footer__inline">
                            <p>-</p>
                        </div>
                        <div className="footer__inline">
                            <a href="">Privacy</a>
                        </div>
                    </span>
                    <span className="footerPiece">
                        <div className="footer__block">
                            <p>Last account activity: 1 mil years</p>
                        </div>
                        <div className="footer__block">
                            <a href="">Details</a>
                        </div>
                    </span>
                </div>
            </div>
        );
    }

}

export default Footer;
