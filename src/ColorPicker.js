const { useEffect, useRef } = React;

const ColorPicker = (props) => {

    const colorInput = useRef(null);


    useEffect(() => {

        jQuery(colorInput.current).wpColorPicker({
            change: function(event, ui) {
                $(event.target).trigger('update', ui.color.toString());

                
            },
    
            clear: function (event) {
                $(event.target).prev().find('input').trigger('update', '');
            }
        })



    }, [])

    


    return <input class="proofratings-color-field" type="text" value="" ref={colorInput} />
}

export default ColorPicker;