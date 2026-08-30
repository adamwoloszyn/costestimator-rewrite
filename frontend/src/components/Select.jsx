import React from 'react';

const Select = (props) => {
    const { timeZone } = Intl.DateTimeFormat().resolvedOptions();
    var zone = new Date().toLocaleTimeString('en-us',{timeZoneName:'short'}).split(' ')[2];

    function openToolTip(id) { 
        var currButton = document.querySelector('#'+id);
        currButton.classList.toggle('active');
    }
    var floatLeft = {
        float: 'left'
    };

    var coverage = "";

    return (
        <div className="form-item">
            <label htmlFor={props.id} >
                {/* {props.title} &nbsp; */}
                <span style={floatLeft} dangerouslySetInnerHTML={{ __html: props.title }}></span>
                { props.hasToolTipInfo !== ''
                    ? <button type="button" id={'toolTip_' + props.id} className="cost-popover " onClick={() => openToolTip('toolTip_' + props.id)}> 
                        i
                        <div className="cost-popover__content">{ props.hasToolTipInfo }</div>
                    </button>
                    : ''
                }
            </label>
            <div className={props.className}>
                <select 
                    id={props.id} 
                    name={props.name}
                    value={props.selectedOption}
                    onChange={props.controlFunc}
                    >
                    {(props.placeholder === "I don't know")
                        ? <option value={props.placeholder}>{props.placeholder}</option>
                        : <option value="Select">{props.placeholder}</option>
                    }
                    {props.options.map(opt => {
                        if(typeof opt === "string"){
                            return(
                                <option key={opt} value={opt} data-coverage={"test1"}>{ opt }</option>
                            );
                        } else {// if(typeof opt == "object"){
                            if(opt.state_coverage !== ""){
                                coverage = opt.state_coverage;
                            }else{
                                coverage = "";
                            }
                            return (
                                <option key={ opt.id } value={ opt.id } data-coverage={coverage}>{ opt.displayName }</option>
                            );
                        }
                    })}
                </select>
            </div>
            { props.name === "insuranceProvider" ? <p>If you don't see your carrier listed, call us at 844.799.3243</p> : ''}
            { props.name === "insuranceState" ? <p>Select your state and the insurance choices above will filter accordingly.</p> : ''}
            { props.name === "CallbackSelection" ? <p className="callbackHours">Business hours are M-F 8am-7pm Eastern Time. </p> : '' }
            { props.name === "CallbackSelection" && zone != "EST" ? <p className="callbackHours">Please note your time zone ( {zone} ) is different than Eastern.  All times shown above are based on Eastern Time.</p> : '' }
        </div>
    );
}

Select.propTypes = {  
//   name: React.PropTypes.string.isRequired,
//   options: React.PropTypes.array.isRequired,
//   selectedOption: React.PropTypes.string,
//   controlFunc: React.PropTypes.func.isRequired,
//   placeholder: React.PropTypes.string
};

export default Select; 