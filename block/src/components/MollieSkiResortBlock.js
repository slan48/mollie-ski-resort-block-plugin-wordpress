import { date } from '@wordpress/date';
import iconArrow from '../../assets/img/icon-arrow.png'
import iconPath from '../../assets/img/icon-path.png'

export default function MollieSkiResortBlock ({ skiResortInfo }) {
	console.log(skiResortInfo);

	const getWeatherIcon = (symbolName) => {
		const icons = {
			Sun: 'fa-solid fa-sun',
			PartlyCloud: 'fa-solid fa-cloud',
			Cloud: 'fa-solid fa-cloud',
			LightCloud: 'fa-solid fa-cloud-sun',
			LightRain: 'fa-solid fa-cloud-sun-rain',
			Snow: 'fa-solid fa-snowflake',
			LightSnow: 'fa-solid fa-snowflake',
			Sleet: 'fa-solid fa-snowflake',
			Drizzle: 'fa-solid fa-cloud-showers-heavy',
			Fog: 'fa-solid fa-cloud',
		};
		return icons[symbolName] ? icons[symbolName] : 'fa-solid fa-sun';
	}

	return (
		<div className='mollie-ski-resort-block'>
			<h4>{skiResortInfo.name}</h4>
			<div className='mollie-ski-resort-block__image-container'>
				<img src={skiResortInfo.image} alt={skiResortInfo.name} />
				<div className='mollie-ski-resort-block__region'>
					<p>{skiResortInfo.region}</p>
					<span>Oppdatert: {date('d.m.Y - H:i', skiResortInfo.last_updated)}</span>
				</div>
			</div>
			<div className='mollie-ski-resort-block__info'>
				{
					skiResortInfo.symbol &&
					<div className='mollie-ski-resort-block__info__weather'>
						<i className={getWeatherIcon(skiResortInfo.symbol.name)}></i>
						<span>{skiResortInfo.symbol.name}</span>
					</div>
				}
				{
					skiResortInfo.temperature_value &&
					<div className='mollie-ski-resort-block__info__temperature'>
						{skiResortInfo.temperature_value}º
					</div>
				}
				{
					skiResortInfo.wind &&
					<div className='mollie-ski-resort-block__info__wind'>
						<div>
							<div className='mollie-ski-resort-block__info__wind-speed'>
								<img src={iconArrow} alt=""/>
								<span className='mollie-ski-resort-block__info__wind-mps'>{skiResortInfo.wind.mps}</span>
								<span>m/s</span>
							</div>
							<div>
								<span>{skiResortInfo.wind.speed}</span>
							</div>
						</div>
					</div>
				}
				{
					skiResortInfo.condition_description &&
					<div className='mollie-ski-resort-block__info__condition'>
						<img src={iconPath} alt=""/>
						<span>{skiResortInfo.condition_description}</span>
					</div>
				}
			</div>
		</div>
	);
}
