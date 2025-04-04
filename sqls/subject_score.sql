-- Use INSERT IGNORE as a safety measure, even after fixing test_attempts
INSERT IGNORE INTO ecat.attempt_scores_by_subject (
    attempt_id,
    subject_id,
    score,
    items_attempted,
    items_correct
)
SELECT
    ta.attempt_id,
    s.id AS subject_id,
    SUM(aa.is_correct) AS calculated_items_correct,
    COUNT(aa.question_id) AS calculated_items_attempted,
    SUM(aa.is_correct) AS calculated_items_correct
FROM
    entranceexam.answer_attempts aa
JOIN
    entranceexam.quizquestions qq ON aa.question_id = qq.id
JOIN
    ecat.subjects s ON qq.subject = s.name
JOIN
    -- This join is now expected to link to UNIQUE attempt_ids
    -- based on the combination of original_session_id and source_lab_id
    ecat.test_attempts ta ON aa.session_id = ta.original_session_id
    -- Crucially, you need to ensure the 'entranceexam' data being read
    -- corresponds to the correct 'source_lab_id' if your setup allows querying
    -- multiple labs' data sources simultaneously. If you run this script
    -- separately for each lab's data source, ensure 'ta.source_lab_id' matches
    -- the lab you are currently processing. For example:
    -- AND ta.source_lab_id = 'CLA' -- Or 'CLB', etc., depending on which lab's data aa/qq represents
WHERE
    qq.subject IS NOT NULL
    AND qq.subject != ''
    AND s.id IS NOT NULL
    -- Add the source_lab_id filter if necessary! See comment in JOIN clause.
    -- AND ta.source_lab_id = 'CURRENT_LAB_BEING_PROCESSED'
GROUP BY
    ta.attempt_id,
    s.id;