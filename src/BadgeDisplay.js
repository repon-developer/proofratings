import store, { ACTIONS } from './Store';

const BadgeDisplay = (props) => {

    const badge_display = Object.assign({
        sites_square: false,
        badge_basic: false,
        sites_rectangle: false,
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
                        <th scope="row" style={{ verticalAlign: "middle" }}>Sites (Square)</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img src={`${proofratings.assets_url}images/widget-style1.png`} alt="Proofratings style" />
                                <label>
                                    <input onChange={() => update_single('sites_square')} className="checkbox-switch checkbox-onoff" checked={badge_display?.sites_square} type="checkbox" />
                                    Embed only
                                </label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Sites (Basic)</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img src={`${proofratings.assets_url}images/sites-basic.png`} alt="Proofratings style" />
                                <label>
                                    <input onChange={() => update_single('badge_basic')} className="checkbox-switch checkbox-onoff" checked={badge_display?.badge_basic} type="checkbox" />
                                    Embed only
                                </label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Sites (Rectangle)</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img src={`${proofratings.assets_url}images/widget-style2.png`} alt="Proofratings style" />
                                <label>
                                    <input className="checkbox-switch checkbox-onoff" onChange={() => update_single('sites_rectangle')} type="checkbox" defaultChecked={badge_display?.sites_rectangle} /> Embed only
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Overall Rating (Rectangle)</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img src={`${proofratings.assets_url}images/floating-badge-style1.png`} alt="Proofratings style" />
                                <label style={{ marginRight: 30 }}>
                                    <input className="checkbox-switch checkbox-onoff" type="checkbox" defaultChecked={badge_display?.overall_rectangle_embed} onChange={() => update_single('overall_rectangle_embed')} /> Embed
                                </label>

                                <label>
                                    <input className="checkbox-switch checkbox-onoff" type="checkbox" defaultChecked={badge_display?.overall_rectangle_float} onChange={() => update_single('overall_rectangle_float')} /> Float
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Overall Rating (Narrow)</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img src={`${proofratings.assets_url}images/floating-badge-style2.png`} alt="Proofratings style" />
                                <label style={{marginRight: 30}}>
                                    <input
                                        type="checkbox"
                                        className="checkbox-switch checkbox-onoff"
                                        onChange={() => update_single('overall_narrow_embed')}
                                        defaultChecked={badge_display?.overall_narrow_embed}
                                    />
                                    Embed
                                </label>

                                <label>
                                    <input
                                        type="checkbox"
                                        className="checkbox-switch checkbox-onoff"
                                        onChange={() => update_single('overall_narrow_float')}
                                        defaultChecked={badge_display?.overall_narrow_float}
                                    />
                                    Float
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style={{ verticalAlign: "middle" }}>Overall Rating CTA Banner</th>
                        <td>
                            <div className="proofratings-image-option">
                                <img src={`${proofratings.assets_url}images/cta-badge.png`} alt="Proofratings style" />
                                <label>
                                    <input
                                        type="checkbox"
                                        defaultChecked={badge_display?.overall_cta_banner}
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
