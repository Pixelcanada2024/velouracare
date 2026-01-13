import React from 'react'

export default function HtmlStringRenderer({htmlString , className = ""}) {
  return (
    <div dangerouslySetInnerHTML={{__html: htmlString}} className={ 'rich-content '  + className} />
  )
}
