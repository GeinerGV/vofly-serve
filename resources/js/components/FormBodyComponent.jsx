import React from "react";

export class FormBody extends React.Component {

    propsToState = ()=>{
        return {...this.state, ...this.props}
    }

    constructor(props) {
        super(props)
    }

    inputsProps = []

    componentDidUpdate(prevProps) {
        if (this.props!=prevProps) {
            this.setState(this.propsToState());
        }
    }

    handleChangeInput = (event) => {
        const target = event.target;
        const value = target.type === 'checkbox' ? target.checked : target.value;
        this.setState({[target.name]: value});
	}

    getInputs = () => {
        let inputs = {};
        this.inputsProps.forEach(val=>{
			inputs[val.name] = <>
				{val.label && val.type!="checkbox" && <label htmlFor={val.id||val.name}>{val.label}</label>}
                <input id={val.name} key={val.name}
                    value={val.type=="checkbox" ? Boolean(this.state[val.name]) : this.state[val.name]} 
					onChange={this.handleChangeInput}  placeholder={val.label||undefined} {...val}
				/>
				{val.label && val.type=="checkbox" && 
					<label className="form-check-label" htmlFor={val.id||val.name}>{val.label}</label>}
			</>
        })
        return inputs;
    }
}