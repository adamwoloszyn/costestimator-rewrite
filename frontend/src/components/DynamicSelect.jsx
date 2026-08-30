import React from 'react';

const DynamicSelect = (props) => (  
    <div className="form-item">
        <label htmlFor={props.id}>{props.title}</label>
        <div className={props.className}>
        <select 
                id={props.id} 
                questionid={props.questionId}
                name={props.name}
                value={props.selectedOption}
                onChange={props.controlFunc}>
                <option value="">{props.placeholder}</option>
                {props.options.map(opt => {
                    if(typeof opt === "string"){
                        return(
                            <option key={opt} value={opt}>{ opt }</option>
                        );
                    } else {// if(typeof opt == "object"){
                        return (
                            <option key={ opt.id } value={ opt.id }>{ opt.displayName }</option>
                        );
                    }
                })}
            </select>
        </div>
        <span id={'error_' + props.id} className="error-message hidden"></span>  
    </div>
);

DynamicSelect.propTypes = {  
//   name: React.PropTypes.string.isRequired,
//   options: React.PropTypes.array.isRequired,
//   selectedOption: React.PropTypes.string,
//   controlFunc: React.PropTypes.func.isRequired,
//   placeholder: React.PropTypes.string
};

export default DynamicSelect; 