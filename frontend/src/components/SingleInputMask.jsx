import React from 'react';
import InputMask from 'react-input-mask';

const SingleInputMask = (props) => {

    function openToolTip(id) { 
        var currButton = document.querySelector('#'+id);
        currButton.classList.toggle('active');
    }
    return (
        <div className="form-item">
            <label htmlFor={props.id}>
                {props.title} &nbsp;
                { props.hasToolTipInfo !== ''
                    ? <button type="button" id={'toolTip_' + props.id} className="cost-popover " onClick={() => openToolTip('toolTip_' + props.id)}> 
                        i
                        <div className="cost-popover__content">{ props.hasToolTipInfo }</div>
                    </button>
                    : ''
                }
            </label>
            <InputMask 
                mask={ props.mask }
                disabled={ !props.isEnabled }
                className={props.className}
                id={props.id}
                name={props.name}
                value={props.content}
                onChange={props.controlFunc}
                placeholder={props.placeholder} 
            />
            <span id={'error_' + props.id} className="error-message hidden"></span>            
        </div>
    );
}

SingleInputMask.propTypes = {  
//   inputType: React.PropTypes.oneOf(['text', 'number']).isRequired,
//   title: React.PropTypes.string.isRequired,
//   name: React.PropTypes.string.isRequired,
//   controlFunc: React.PropTypes.func.isRequired,
//   content: React.PropTypes.oneOfType([
//     React.PropTypes.string,
//     React.PropTypes.number,
//   ]).isRequired,
//   placeholder: React.PropTypes.string,
};

export default SingleInputMask;  