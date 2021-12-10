import ColorPicker from "./ColorPicker";

import Border from "./Border";

const BadgeSquare = (props) => {

    const sites_square = Object.assign({customize: true}, props.sites_square);

    const handle_field = (name, data) => {

        

        props.onUpdate('sites_square', {...sites_square, [name]: data});
    }

    //console.log(sites_square)

    return (
        <React.Fragment>
            <div>
                <table className="form-table">
                    <tbody>
                        <tr>
                            <th scope="row" style={{ verticalAlign: "middle" }}>
                                Shortcode
                                <p className="description" style={{ fontWeight: "normal" }}>Use shortcode where you want to display review widgets</p>
                            </th>
                            <td>
                                <code className="shortocde-area">[proofratings_widgets style="square"]</code>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <label>
                    <input
                        type="checkbox"
                        checked={sites_square.customize}
                        className="checkbox-switch checkbox-yesno"
                        onChange={() => handle_field('customize', !sites_square.customize)}
                    />
                    Customize (this will customize all badges)
                </label>
                
                <div className="gap-30" />
                <div id="square-badge-customize">
                    echo do_shortcode( '[proofratings_widgets
                    id="proofratings-badge-square"]');
                    <table className="form-table">
                        <tbody>
                            <tr>
                                <th scope="row">Logo Color</th>
                                <td><ColorPicker name="logo_color" onUpdate={handle_field} color="#81d742" /></td>
                            </tr>
                            <tr>
                                <th scope="row">Star Color</th>
                                <td><ColorPicker name="star_color" onUpdate={handle_field} /></td>
                            </tr>
                            <tr>
                                <th scope="row">Text Color</th>
                                <td><ColorPicker onUpdate={(textcolor) => handle_field({textcolor})} /></td>
                            </tr>
                            <tr>
                                <th scope="row">Review count text color</th>
                                <td><ColorPicker onUpdate={(review_color_textcolor) => handle_field({review_color_textcolor})} /></td>
                            </tr>
                            <tr>
                                <th scope="row">Background Color</th>
                                <td><ColorPicker onUpdate={(background_color) => handle_field({background_color})} /></td>
                            </tr>

                            <Border name="border" border={sites_square.border} onUpdate={handle_field} />
                            
                        </tbody>
                        <tbody>
                            <tr>
                                <th scope="row">Shadow</th>
                                <td>
                                    <input
                                        className="checkbox-switch"
                                        name="proofratings_badges_square[shadow]"
                                        defaultValue="no"
                                        type="hidden"
                                    />
                                    <input
                                        className="checkbox-switch"
                                        name="proofratings_badges_square[shadow]"
                                        defaultValue="yes"
                                        type="checkbox"
                                    />
                                </td>
                            </tr>
                        </tbody>
                        <tbody
                            id="proofratings-badges-sites-square-shadow-options"
                            style={{ display: "none" }}
                        >
                            <tr>
                                <th scope="row">Shadow Color</th>
                                <td />
                            </tr>
                            <tr>
                                <th scope="row">Shadow Hover Color</th>
                                <td />
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </React.Fragment>
    );
};

export default BadgeSquare;
