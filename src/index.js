import "./index.scss"
import { useState, useEffect } from "react"
import { use, useSelect } from "@wordpress/data"
import { useBlockProps } from "@wordpress/block-editor"
import apiFetch from "@wordpress/api-fetch"

wp.blocks.registerBlockType("ourplugin/featured-professor", {
  title: "Professor Callout",
  description: "Include a short description and link to a professor of your choice",
  icon: "welcome-learn-more",
  category: "common",
  attributes: {
    profId: {
      type: "string"
    },
  },
  edit: EditComponent,
  save: function () {
    return null
  }
})

/**
 * A React component for editing a featured professor block.
 *
 * @param {object} props - The component's props, including the block's attributes.
 * @return {JSX.Element} The JSX element representing the edit component.
 */
function EditComponent(props) {
  const blockProps = useBlockProps({
    className: "featured-professor-wrapper",
  })

  const [thePreview, setThePrview] = useState("")

  useEffect(() => {
    if (props.attributes.profId) {
      updateTheMeta();
      async function go() {
        const response = await apiFetch({
          path: `/featuredProfessor/v1/getHTML?profId=${props.attributes.profId}`,
          method: "GET"
        })
        setThePrview(response)
      }
      go()
    }
  }, [props.attributes.profId])

  useEffect(() => {
    return () => {
      updateTheMeta()
    }
  }, [])

  /**
   * Updates the post meta with the featured professor IDs.
   *
   * This function retrieves the IDs of all featured professors in the current post,
   * removes duplicates, and updates the post meta with the resulting array of IDs.
   *
   * @return {void}
   */
  function updateTheMeta() {
    const profsForMeta = wp.data.select("core/editor").getBlocks()
      .filter(x => x.name == "ourplugin/featured-professor")
      .map(x => x.attributes.profId)
      .filter((x, index, arr) => {
        return arr.indexOf(x) == index
      })

    console.log(profsForMeta)
    wp.data.dispatch("core/editor").editPost({ meta: { featured_professor: profsForMeta } })
  }

  const allProfs = useSelect((select) => {
    return select("core").getEntityRecords("postType", "professor", { per_page: -1 })
  })

  if (allProfs == undefined) return <p {...blockProps}>loading...</p>

  return (
    <div {...blockProps}>
      <div className="professor-select-container">
        <select onChange={(e) => props.setAttributes({ profId: e.target.value })}>
          <option value="">Select a professor</option>
          {allProfs.map(prof => {
            return (
              <option value={prof.id} selected={props.attributes.profId == prof.id}>
                {prof.title.rendered}
              </option>
            )
          })}
        </select>
      </div>
      <div dangerouslySetInnerHTML={{ __html: thePreview }}></div>
    </div>
  )
}