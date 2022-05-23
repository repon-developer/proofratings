import store, { ACTIONS } from './Store';

const BadgeDisplay = (props) => {

    const badge_display = Object.assign({
        square: false,
        basic: false,
        icon: false,
        rectangle: false,
        overall_cta_banner: false,
        overall_rectangle_embed: false,
        overall_rectangle_float: false,
        overall_narrow_embed: false,
        overall_narrow_float: false
    }, props?.badge_display);

    const update_single = (name) => {
        badge_display[name] = !badge_display[name];
        store.dispatch({ type: ACTIONS.BADGE_DISPLAY, payload: badge_display });
    }

    return (
        <React.Fragment>
            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Square</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img src={`${proofratings.assets_url}images/widget-style1.png`} alt="Proofratings style" />
                                <label>
                                    <input onChange={() => update_single('square')} className="checkbox-switch checkbox-onoff" checked={badge_display?.square} type="checkbox" />
                                    Embed only
                                </label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Basic</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img style={{marginLeft: 5}} src={`${proofratings.assets_url}images/sites-basic.png`} alt="Proofratings" />
                                <label>
                                    <input onChange={() => update_single('basic')} className="checkbox-switch checkbox-onoff" checked={badge_display?.basic} type="checkbox" />
                                    Embed only
                                </label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Icon</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img style={{marginLeft: 5, padding: 10, width: 140, backgroundColor: '#fff', borderRadius: 5}} src={`${proofratings.assets_url}images/sites-icon.jpg`} alt="Proofratings" />
                                <label>
                                    <input onChange={() => update_single('icon')} className="checkbox-switch checkbox-onoff" checked={badge_display?.icon} type="checkbox" />
                                    Embed only
                                </label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Rectangle</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img src={`${proofratings.assets_url}images/widget-style2.png`} alt="Proofratings style" />
                                <label>
                                    <input className="checkbox-switch checkbox-onoff" onChange={() => update_single('rectangle')} type="checkbox" checked={badge_display?.rectangle} /> Embed only
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Overall Rectangle</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img src={`${proofratings.assets_url}images/floating-badge-style1.png`} alt="Proofratings style" />
                                <label style={{ marginRight: 30 }}>
                                    <input className="checkbox-switch checkbox-onoff" type="checkbox" checked={badge_display?.overall_rectangle_embed} onChange={() => update_single('overall_rectangle_embed')} /> Embed
                                </label>

                                <label>
                                    <input className="checkbox-switch checkbox-onoff" type="checkbox" checked={badge_display?.overall_rectangle_float} onChange={() => update_single('overall_rectangle_float')} /> Float
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Overall Narrow</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img src={`${proofratings.assets_url}images/floating-badge-style2.png`} alt="Proofratings style" />
                                <label style={{marginRight: 30}}>
                                    <input
                                        type="checkbox"
                                        className="checkbox-switch checkbox-onoff"
                                        onChange={() => update_single('overall_narrow_embed')}
                                        checked={badge_display?.overall_narrow_embed}
                                    />
                                    Embed
                                </label>

                                <label>
                                    <input
                                        type="checkbox"
                                        className="checkbox-switch checkbox-onoff"
                                        onChange={() => update_single('overall_narrow_float')}
                                        checked={badge_display?.overall_narrow_float}
                                    />
                                    Float
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Overall CTA Banner</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img src={`${proofratings.assets_url}images/cta-badge.png`} alt="Proofratings style" />
                                <label>
                                    <input
                                        type="checkbox"
                                        checked={badge_display?.overall_cta_banner}
                                        className="checkbox-switch checkbox-onoff"
                                        onChange={() => update_single('overall_cta_banner')}
                                    /> Float only
                                </label>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </React.Fragment>
    );
};

export default BadgeDisplay;
