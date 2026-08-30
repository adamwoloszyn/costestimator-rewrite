import React from 'react';

const SingleInput = (props) => {
    
    function openToolTip(id) { 
        var currButton = document.querySelector('#'+id);
        currButton.classList.toggle('active');
    }
    if(props.inputType === 'hidden'){
        return (
            <input
                id={props.id}
                className={props.className}
                name={props.name}
                type={props.inputType}
                value={props.content}
                onChange={props.controlFunc}
                placeholder={props.placeholder} />
        );
    }else{
        return (
            <div className="form-item">
                <label htmlFor={props.id}>
                    {props.title} &nbsp;
                    { props.hasToolTipInfo !== ''
                        ? <button type="button" id={'toolTip_' + props.id} className="cost-popover tooltip" onClick={() => openToolTip('toolTip_' + props.id)}> 
                            i
                            <div className="cost-popover__content">{ props.hasToolTipInfo }</div>
                        </button>
                        : ''
                    }
                </label>
                <input
                    maxLength="255"
                    disabled={ !props.isEnabled }
                    id={props.id}
                    className={props.className}
                    name={props.name}
                    type={props.inputType}
                    value={props.content}
                    onChange={props.controlFunc}
                    placeholder={props.placeholder} />
                <span id={'error_' + props.id} className="error-message hidden"></span>
                { props.name === "Member_Id" ? <span id={'alpha_error_' + props.id} className="error-message hidden">You must use only letters or numbers.</span> : ''}
            </div>
        );
    }
}

SingleInput.propTypes = {  
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

export default SingleInput;  