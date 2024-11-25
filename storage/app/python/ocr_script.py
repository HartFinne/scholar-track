import sys
import os
import gc
import logging
from paddleocr import PaddleOCR
from PIL import Image

# Set up logging
logging.basicConfig(level=logging.INFO)

def split_image(image_path, num_parts=5):
    """Split the image into 5 parts."""
    img = Image.open(image_path)
    width, height = img.size

    # Calculate size for each part (a simple approach)
    part_width = width // 3  # Divide the width into 3 columns
    part_height = height // 2  # Divide the height into 2 rows

    parts = []
    # Ensure valid cropping and split into 5 parts
    # Part 1: Top-left section
    parts.append(img.crop((0, 0, part_width * 2, part_height)))
    
    # Part 2: Top-right section
    parts.append(img.crop((part_width * 2, 0, width, part_height)))

    # Part 3: Bottom-left section
    parts.append(img.crop((0, part_height, part_width * 2, height)))

    # Part 4: Bottom-right section
    parts.append(img.crop((part_width * 2, part_height, width, height)))

    # Part 5: A combination of the remaining part (adjust accordingly)
    # This part can be added as needed, depending on how you want to handle the leftovers
    if width > part_width * 2 and height > part_height * 2:
        parts.append(img.crop((part_width, part_height, width - part_width, height - part_height)))

    part_paths = []
    for idx, part in enumerate(parts):
        part_path = image_path.replace('.png', f'_part_{idx}.png')
        part.save(part_path)
        part_paths.append(part_path)

    # Clear image data from memory once parts are split
    del img
    gc.collect()

    return part_paths


def extract_text(image_path, ocr):
    """Extract text from an image using PaddleOCR."""
    logging.info(f"Processing image: {image_path}")
    try:
        # Perform OCR
        result = ocr.ocr(image_path, cls=True)

        # Extract text from OCR result
        extracted_text = ''
        for line in result[0]:
            word_info = line[1]
            word_text = word_info if isinstance(word_info, str) else str(word_info[0])
            extracted_text += word_text + '\n'

        # Clear OCR result to free memory
        del result
        gc.collect()

        return extracted_text

    except Exception as e:
        logging.error(f"Error processing {image_path}: {str(e)}")
        return ''  # Return empty text if OCR fails on this part

def save_text_to_file(image_path, extracted_text):
    """Save the extracted text to a file."""
    output_file = image_path.replace('.png', '.txt')
    try:
        with open(output_file, 'w') as f:
            f.write(extracted_text)
        logging.info(f"Extracted text saved to: {output_file}")
    except Exception as e:
        logging.error(f"Error saving text to file {output_file}: {str(e)}")

def process_image(image_path, ocr):
    """Process a single image by splitting, extracting text, and saving to file."""
    parts = split_image(image_path)  # Split the image into parts
    full_text = ''

    for part in parts:
        extracted_text = extract_text(part, ocr)
        full_text += extracted_text

        # Clear extracted text after each part to free memory
        del extracted_text
        gc.collect()

    # Save the extracted text to a file
    save_text_to_file(image_path, full_text)
    return full_text

if __name__ == '__main__':
    # Initialize PaddleOCR with minimal resources
    ocr = PaddleOCR(
        use_angle_cls=True, 
        lang='en', 
        det_algorithm='DB', 
        rec_algorithm='CRNN', 
        rec_batch_num=1,  # Avoid using too many batches at once
        table=False, 
        formula=False, 
        layout=False, 
        max_text_length=10, 
        cpu_threads=1  # Limit the number of threads to reduce memory usage
    )

    # Get a list of image paths from command line arguments
    image_paths = sys.argv[1:]

    for image_path in image_paths:
        # Process each image individually
        logging.info(f"Starting processing for {image_path}")
        extracted_text = process_image(image_path, ocr)

        # Optionally print the extracted text for each image
        print(extracted_text)

        # Force garbage collection to free memory explicitly after each image
        gc.collect()

    # Clean up PaddleOCR instance
    del ocr
    gc.collect()
