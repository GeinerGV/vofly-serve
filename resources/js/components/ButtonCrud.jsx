import React from 'react';

const ButtonCrud = ({className, tipo, ...props}) => {
	const baseClassName = "ml-1 btn btn-"+(tipo||"primary");
	const resultClassName = baseClassName+(className?" "+className:"")
	return <button type="button" className={resultClassName} onClick={props.onClick} {...props}>
		{props.children}
	</button>
}

export default ButtonCrud
