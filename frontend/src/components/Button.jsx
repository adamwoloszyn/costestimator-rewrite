import React from 'react';

const Button = (props) => (  

    <div className="cost-button-container-right">
        {/* add deactivated form cost-btn to show disabled state */}
        <button disabled={ !props.isEnabled } className="cost-btn" onClick={ () => props.controlFunc() }>{ props.label }</button>  
    </div>
);

Button.propTypes = {  
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

export default Button;  